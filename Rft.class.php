<?php
/*------------------------------------------------------------*/
class Rft extends Mcontroller {
	/*------------------------------------------------------------*/
	private $user = null;
	private $adminNumOps = 50;
	/*------------------------------------------------------------*/
	public function __construct() {
		parent::__construct();
		$this->init();
		$this->setUser();
		$this->preAuthenticate();
		$this->Mview->register_modifier('nickname', array($this, 'nickname',));
		$this->Mview->assign('adminNumOps', $this->adminNumOps);
	}
	/*------------------------------------------------------------*/
	protected function before() {
		parent::before();
		header('Content-type: text/html; charset=UTF-8');
		$this->Mview->showTpl("header.tpl");
	}
	/*------------------------------------------------------------*/
	protected function after() {
		parent::after();
		$this->Mview->showTpl("footer.tpl");
	}
	/*------------------------------------------------------------*/
	private function init() {
		$tables = array(
			"users",
			"followees",
			"bands",
			"artists",
			"bandArtists",
			"favoriteBands",
			"favoriteArtists",
		);
		foreach ( $tables as $t )
			if ( ! $this->Mmodel->isTable($t) ) {
				Mview::msg("Creating table $t");
				$sql = file_get_contents("sql/$t.crtable.sql");
				$this->Mmodel->_sql($sql);
				$dataFile = "sql/$t.data.sql";
				if ( file_exists($dataFile) ) {
					$lines = Mutils::Mfile($dataFile);
					$cnt = count($lines);
					Mview::msg("Loading $cnt rows from $dataFile");
					foreach ( $lines as $line )
						$this->Mmodel->_sql($line);
				}
			}
	}
	/*------------------------------------------------------------*/
	private function setTitle($title) {
		$this->Mview->assign("title", $title);
	}
	/*------------------------------------------------------------*/
	private function setMeta($title, $words) {
		if ( ! $words )
			return;
		$wordsList = implode(", ", $words);
		$this->Mview->assign("metaTitle", $title);
		$this->Mview->assign("metaKeywords", "$title, $wordsList");
		$this->Mview->assign("metaDescription", "$title: $wordsList");
		$u = new Mutils;
		$searchWords = $u->array_truncate($words, 4); // too many words makes google find nothing in some cases
		$searchWordList = implode(" ", $searchWords);
		$this->Mview->assign("searchQuery", "wikipedia +\"$title\" $searchWordList");
	}
	/*------------------------------------------------------------*/
	public function help() {
		$this->Mview->showTpl("help.tpl");
	}
	/*------------------------------------------------------------*/
	private function _addArtistToFavorites($rftId, $artistId) {
		$is = $this->Mmodel->getInt("select count(*) from favoriteArtists where rftId = $rftId and artistId = $artistId");
		if ( ! $is )
			$this->Mmodel->dbInsert("favoriteArtists", array(
				"rftId" => $rftId,
				"artistId" => $artistId,
			));
	}
	/*------------------------------*/
	public function addArtistToFavorites() {
		if ( ! $this->validateUser() ) {
			$this->home();
			return;
		}
		$rftId = $_SESSION['rftId'];
		$artistId = $_REQUEST['artistId'];
		$this->_addArtistToFavorites($rftId, $artistId);
		$this->user['favoriteArtists'][] = $artistId;
		$this->Mview->assign("user", $this->user);
		$this->userHome($rftId);
	}
	/*------------------------------*/
	public function removeFavoriteArtist() {
		$rftId = $_SESSION['rftId'];
		$artistId = $_REQUEST['artistId'];
		$sql = "delete from favoriteArtists where rftId = $rftId and artistId = $artistId";
		$this->Mmodel->_sql($sql);
		$ind = array_search($artistId, $this->user['favoriteArtists']);
		unset($this->user['favoriteArtists'][$ind]);
		$this->Mview->assign("user", $this->user);
		$this->userHome($rftId);
	}
	/*------------------------------*/
	public function removeFavoriteBand() {
		$rftId = $_SESSION['rftId'];
		$bandId = $_REQUEST['bandId'];
		$sql = "delete from favoriteBands where rftId = $rftId and bandId = $bandId";
		$this->Mmodel->_sql($sql);
		$ind = array_search($bandId, $this->user['favoriteBands']);
		unset($this->user['favoriteBands'][$ind]);
		$this->Mview->assign("user", $this->user);
		$this->userHome($rftId);
	}
	/*------------------------------*/
	private function _addBandToFavorites($rftId, $bandId) {
		$is = $this->Mmodel->getInt("select count(*) from favoriteBands where rftId = $rftId and bandId = $bandId");
		if ( ! $is )
			$this->Mmodel->dbInsert("favoriteBands", array(
				"rftId" => $rftId,
				"bandId" => $bandId,
			));
	}
	/*------------------------------*/
	public function addBandToFavorites() {
		if ( ! $this->validateUser() ) {
			$this->home();
			return;
		}
		$rftId = $_SESSION['rftId'];
		$bandId = $_REQUEST['bandId'];
		$this->_addBandToFavorites($rftId, $bandId);
		$this->user['favoriteBands'][] = $bandId;
		$this->Mview->assign("user", $this->user);
		$this->userHome($rftId);
	}
	/*------------------------------*/
	public function unfollow() {
		if ( ! $this->validateUser() ) {
			$this->home();
			return;
		}
		$rftId = $_SESSION['rftId'];
		$followee = $_REQUEST['userId'];
		$sql = "delete from followees where rftId = $rftId and followee = $followee";
		$this->Mmodel->_sql($sql);
		$this->userHome($rftId);
	}
	/*------------------------------*/
	public function follow() {
		if ( ! $this->validateUser() ) {
			$this->home();
			return;
		}
		$rftId = $_SESSION['rftId'];
		$followee = $_REQUEST['userId'];
		$is = $this->Mmodel->getInt("select count(*) from followees where rftId = $rftId and followee = $followee");
		if ( ! $is )
			$this->Mmodel->dbInsert("followees", array(
				"rftId" => $rftId,
				"followee" => $followee,
			));
		$this->userHome($rftId);
	}
	/*------------------------------------------------------------*/
	private function loadUser($rftId = null) {
		if ( ! $rftId )
			$rftId = @$_SESSION['rftId'];
		if ( ! $rftId ) {
			$this->Mview->error("loadUser: rftId not set");
			return(false);
		}
		if ( $this->user && $this->user['id'] == $rftId )
			return(true);
		$sql = "select * from users where id = $rftId";
		$this->user = $this->Mmodel->getRow($sql);
		if ( ! $this->user )
			return(false);
		if ( ! $this->user['nickname'] )
			$this->user['nickname'] = $rftId;
		$this->user['favoriteBands'] = $this->Mmodel->getStrings("select bandId from favoriteBands where rftId = $rftId");
		$this->user['favoriteArtists'] = $this->Mmodel->getStrings("select artistId from favoriteArtists where rftId = $rftId");
		$this->Mview->assign("user", $this->user);
		return(true);
	}
	/*----------------------------------------*/
	private function validateUser() {
		// the logic is in the templates and the js code
		// this prevents spoofing by typing urls or programatically
		// trying to bypass into the db
		if ( isset($_SESSION['rftId']) &&
				isset($this->user['id']) &&
					$_SESSION['rftId'] == $this->user['id'] )
			return(true);

		$checkCaptcha = true;
		if ( $checkCaptcha ) {
			$captchaSet = $_SESSION['captchaSet'];
			$captchaEntered = @$_REQUEST['captchaEntered'];
			if ( ! $captchaSet || $captchaSet != $captchaEntered ) {
				$this->Mview->error("Captcha incorrect");
				return(false);
			}
		}
			
		 $rftId = $this->Mmodel->dbInsert("users", array(
						"passwd" => "".rand(123456, 987654),
		 				"created" => date("Ymd"),
				));
		 $this->setUser($rftId);
		 return(true);
	}
	/*----------------------------------------*/
	private function setUser($rftId = null) {
		if ( $rftId ) {
			// switch user to this rftId
			$_SESSION['rftId'] = $rftId;
			setcookie("rftId", $rftId, time(0) + 10*365*24*60*60);
		}
		if ( isset($_SESSION['rftId']) ) {
			$this->loadUser();
			unset($_SESSION['captchaSet']);
			return;
		}

		if ( isset($_COOKIE['rftId']) && $this->loadUser($_COOKIE['rftId']) ) {
			unset($_SESSION['captchaSet']);
			$_SESSION['rftId'] = $_COOKIE['rftId'];
		}
	}
	/*------------------------------------------------------------*/
	public function index() {
		$this->home();
	}
	/*------------------------------------------------------------*/
	public function changeNickname() {
		$rftId = $_SESSION['rftId'];
		$nickname = trim(ucwords(preg_replace('/\s+/', ' ', $_REQUEST['nickname'])));
		if ( $nickname ) {
			$this->Mmodel->dbUpdate("users", $rftId, array("nickname" => $nickname,));
			$this->user['nickname'] = $nickname;
			$this->Mview->assign("user", $this->user);
		}
		$this->userHome($rftId);
	}
	/*------------------------------------------------------------*/
	private function visitorHome() {
		$bands = $this->Mmodel->getRows("select * from bands order by name limit 20");
		$artists = $this->Mmodel->getRows("select * from artists order by name limit 20");
		$this->setTitle("Visitor");
		$this->Mview->showTpl("home.tpl", array(
			'bands' => $bands,
			'artists' => $artists,
			'mostActive' => $this->mostActive(),
			'latelyActive' => $this->latelyActive(),
		));
	}
	/*----------------------------------------*/
	private function userBands($rftId) {
		$favInSql = "select bandId from favoriteBands where rftId = $rftId";
		$favorites = $this->Mmodel->getRows("select * from bands where id in ( $favInSql ) order by name");
		$myLatest = $this->Mmodel->getRows("select * from bands where createdBy = $rftId and id not in ( $favInSql ) order by id desc limit 10");
		// it is very unlikely for a latest to also be most popular
		$latest = $this->Mmodel->getRows("select * from bands where id not in ( $favInSql ) and createdBy != $rftId order by id desc limit 5");
		return(array_merge($favorites, $myLatest, $latest));
	}
	/*------------------------------*/
	private function userArtists($rftId) {
		$favInSql = "select artistId from favoriteArtists where rftId = $rftId";
		$favorites = $this->Mmodel->getRows("select * from artists where id in ( $favInSql ) order by name");
		$myLatest = $this->Mmodel->getRows("select * from artists where createdBy = $rftId and id not in ( $favInSql ) order by id desc limit 10");
		// it is very unlikely for a latest to also be most popular
		$latest = $this->Mmodel->getRows("select * from artists where id not in ( $favInSql ) and createdBy != $rftId order by id desc limit 5");
		return(array_merge($favorites, $myLatest, $latest));
	}
	/*------------------------------*/
	private function followers($rftId) {
		$sql = "select u.* from users u, followees f where u.id = f.rftId and f.followee = $rftId order by id desc";
		$followers = $this->Mmodel->getRows($sql);
		return($followers);
	}
	/*------------------------------*/
	private function followees($rftId) {
		$sql = "select u.* from users u, followees f where u.id = f.followee and f.rftId = $rftId order by id desc";
		$followees = $this->Mmodel->getRows($sql);
		return($followees);
	}
	/*------------------------------*/
	private function mostActive() {
		$sql = "select * from users order by numOps desc limit 5";
		$users = $this->Mmodel->getRows($sql);
		return($users);
	}
	/*------------------------------*/
	private function latelyActive() {
		$sql = "select * from users order by lastOp desc limit 5";
		$users = $this->Mmodel->getRows($sql);
		return($users);
	}
	/*------------------------------*/
	public function userHome($rftId = null) {
		if ( ! $rftId && ! isset($_REQUEST['userId']) ) {
			$this->Mview->error("userHome: No userId");
			$this->visitorHome();
			return;
		}
		if ( ! $rftId )
			$rftId = $_REQUEST['userId'];

		if ( $this->user && $this->user['id'] == $rftId ) {
			$homeUser = $this->user;
		} else {
			$sql = "select * from users where id = $rftId";
			$homeUser = $this->Mmodel->getRow($sql);
			if ( ! $homeUser['nickname'] )
				$homeUser['nickname'] = $homeUser['id'];
		}
		$bands = $this->Mmodel->getRows("select * from bands where createdBy = $rftId order by id desc");
		$artists = $this->Mmodel->getRows("select * from artists where createdBy = $rftId order by id desc");
		$this->setTitle("{$homeUser['nickname']}'s home");
		$homeUser['favoriteBands'] = $this->Mmodel->getStrings("select bandId from favoriteBands where rftId = $rftId");
		$homeUser['favoriteArtists'] = $this->Mmodel->getStrings("select artistId from favoriteArtists where rftId = $rftId");
		$this->Mview->showtpl("home.tpl", array(
			'homeUser' => $homeUser,
			'bands' => $this->userBands($rftId),
			'artists' => $this->userArtists($rftId),
			'followees' => $this->followees($rftId),
			'followers' => $this->followers($rftId),
			'mostActive' => $this->mostActive(),
			'latelyActive' => $this->latelyActive(),
		));
		return(true);
	}
	/*------------------------------*/
	public function home() {
		if ( isset($_SESSION['rftId']) )
			$this->userHome($_SESSION['rftId']);
		else
			$this->visitorHome();
	}
	/*------------------------------------------------------------*/
	/**
	 * prepare for athentication
	 * set a captcha if there is no user id
	 */
	private function preAuthenticate() {
		if ( isset($_SESSION['rftId']) )
			return;
		if ( isset($_SESSION['captchaSet']) )
			return;
		$cmd = "find images/Captcha -name 'captcha.*.jpg' -amin +15 -exec rm {} \;";
		`$cmd`;

		$captcha = rand(1024, 8196)."";

		$img = imagecreatefromjpeg("images/captcha.jpg");

		$LineColor = imagecolorallocate($img, 0, 0, 128);
		$TextColor = imagecolorallocate($img, 0, 0, 255);

		for($i=0;$i<5;$i++)
			imageline($img, rand(1,85), 1, rand(1,85), 32, $LineColor);
		$n = strlen($captcha);
		for($i=0;$i<$n;$i++)
			imagestring($img, 6, $i*13 + 8, 10 + rand(-2,2), $captcha[$i], $TextColor);


		$fname = "captcha.".time().".".rand(1000,9999).".jpg";
		$fpath = "images/Captcha/$fname";
		$created = imagejpeg($img, $fpath);
		if ( $created ) {
			$_SESSION['captchaSet'] = $captcha;
			$_SESSION['captchaFile'] = $fpath;
		} else {
			$this->Mview->error("Cannot create captcha $fpath");
		}
	}
	/*----------------------------------------*/
	public function switchId() {
		$newRftId = trim($_REQUEST['nickname']);
		$passwd = trim($_REQUEST['passwd']);
		if ( ! $newRftId || ! $passwd ) {
			$this->home();
			return;
		}
		$dbPasswd = $this->Mmodel->getString("select passwd from users where id = $newRftId");
		if ( $dbPasswd != $passwd ) {
			$this->Mview->error("Switch User Failed");
			$this->home();
			return;
		}
		$this->setUser($newRftId);
		$this->home();

	}
	/*------------------------------------------------------------*/
	private function wordList($rows, $fname) {
		$ret = array();
		if ( ! $rows )
			return($ret);
		foreach ( $rows as $row )
			$ret[] = $row[$fname];
		return($ret);
	}
	/*------------------------------------------------------------*/
	public function band($bandId = null) {
		if ( ! $bandId )
			$bandId = $_REQUEST['bandId'];
		$band = $this->Mmodel->getRow("select * from bands where id = $bandId");
		$this->setTitle($band['name']);
		$artists = $this->Mmodel->getRows("select a.* from artists a, bandArtists ba where a.id = ba.artistId and ba.bandId = $bandId order by a.name");
		$artistNames = $this->wordList($artists, "name");
		$this->setMeta($band['name'], $artistNames);
		if ( $this->user ) {
			$userId = $this->user['id'];
			$isFavorite = $this->Mmodel->getInt("select id from favoriteBands where rftId = $userId and bandId = $bandId");
		} else
			$isFavorite = false;
		$this->Mview->showTpl("band.tpl", array(
			"band" => $band,
			"artists" => $artists,
			"isFavorite" => $isFavorite,
		));
	}
	/*------------------------------------------------------------*/
	public function artist($artistId = null) {
		if ( ! $artistId )
			$artistId = $_REQUEST['artistId'];
		$artist = $this->Mmodel->getRow("select * from artists where id = $artistId");
		$this->setTitle($artist['name']);
		$bands = $this->Mmodel->getRows("select b.* from bands b, bandArtists ba where b.id = ba.bandId and ba.artistId = $artistId order by b.name");
		$bandNames = $this->wordList($bands, "name");
		$this->setMeta($artist['name'], $bandNames);
		if ( $this->user ) {
			$userId = $this->user['id'];
			$isFavorite = $this->Mmodel->getInt("select id from favoriteArtists where rftId = $userId and artistId = $artistId");
		} else
			$isFavorite = false;
		$this->Mview->showTpl("artist.tpl", array(
			"artist" => $artist,
			"bands" => $bands,
			"isFavorite" => $isFavorite,
		));
	}
	/*------------------------------------------------------------*/
	/*------------------------------------------------------------*/
	private function canonize($str) {
		$ret = $str;
		$ret = preg_replace('/[#<>\[\]@$%^*()\_=|:;]*/', '', $str);
		$ret = preg_replace('/\s+/', ' ', $ret);
		$ret = trim($ret, ", ");
		$ret = ucwords($ret);
		return($ret);
	}
	/*------------------------------------------------------------*/
	private function updateStats() {
		$rftId = $_SESSION['rftId'];
		$today = date("Ymd");
		$sql = "update users set numOps = numOps + 1, lastOp = $today where id = $rftId";
		$this->Mmodel->_sql($sql);
		/*	$this->user['numOps']++;	*/
		$this->user['numOps'] = $this->Mmodel->getInt("select numOps from users where id = $rftId");
		$this->Mview->assign("user", $this->user);
		
	}
	/*------------------------------------------------------------*/
	private function getArtist($artistName) {
		$canonical = $this->canonize($artistName);
		$dbStr = $this->Mmodel->str($canonical);
		$id = $this->Mmodel->getInt("select id from artists where name = '$dbStr'");
		if ( $id )
			return($id);
		$ret = $this->Mmodel->dbInsert("artists", array(
			"name" => $canonical,
			"createdOn" => date("Ymd"),
			"createdBy" => @$_SESSION['rftId'],
		));
		$this->updateStats();
		return($ret);
	}
	/*------------------------------------------------------------*/
	private function getBand($bandName) {
		$canonical = $this->canonize($bandName);
		$dbStr = $this->Mmodel->str($canonical);
		$id = $this->Mmodel->getInt("select id from bands where name = '$dbStr'");
		if ( $id )
			return($id);
		$ret = $this->Mmodel->dbInsert("bands", array(
			"name" => $canonical,
			"createdOn" => date("Ymd"),
			"createdBy" => @$_SESSION['rftId'],
		));
		$this->updateStats();
		return($ret);
	}
	/*------------------------------------------------------------*/
	public function changeBand() {
		$bandId = $_REQUEST['bandId'];
		if ( ! $this->validateUser() ) {
			$this->band($bandId);
			return;
		}
		$bandName = $_REQUEST['bandName'];
		$canonical = $this->canonize($bandName);
		$dbStr = $this->Mmodel->str($canonical);
		$is = $this->Mmodel->getInt("select id from bands where name = '$dbStr'");
		if ( $is == $bandId )
			$this->Mview->msg("$canonical: No change");
		elseif ( $is )
			$this->Mview->error("$canonical already exists");
		else
			$this->Mmodel->dbUpdate("bands", $bandId, array("name" => $canonical));
		$this->band($bandId);
	}
	/*------------------------------------------------------------*/
	public function changeArtist() {
		$artistId = $_REQUEST['artistId'];
		if ( ! $this->validateUser() ) {
			$this->artist($artistId);
			return;
		}
		$artistName = $_REQUEST['artistName'];
		$canonical = $this->canonize($artistName);
		$dbStr = $this->Mmodel->str($canonical);
		$is = $this->Mmodel->getInt("select count(*) from artists where name = '$dbStr'");
		if ( $is )
			$this->Mview->error("$canonical already exists");
		else
			$this->Mmodel->dbUpdate("artists", $artistId, array("name" => $canonical));
		$this->artist($artistId);
	}
	/*------------------------------------------------------------*/
	public function addBand() {
		if ( ! $this->validateUser() ) {
			$this->home();
			return;
		}

		$bandId = $this->getBand($_REQUEST['bandName']);
		$this->_addBandToFavorites($this->user['id'], $bandId);
		$this->band($bandId);
	}
	/*------------------------------------------------------------*/
	public function addArtist() {
		if ( ! $this->validateUser() ) {
			$this->home();
			return;
		}
		$artistId = $this->getArtist($_REQUEST['artistName']);
		$this->_addArtistToFavorites($this->user['id'], $artistId);
		$this->artist($artistId);
	}
	/*------------------------------------------------------------*/
	public function search() {
		$searchTerm = $this->Mmodel->str(trim(ucwords(preg_replace('/\s+/', ' ', $_REQUEST['searchTerm']))));

		if ( $bandId = $this->Mmodel->getInt("select id from bands where name = '$searchTerm'") ) {
			$this->band($bandId);
			return;
		}
		if ( $artistId = $this->Mmodel->getInt("select id from artists where name = '$searchTerm'") ) {
			$this->artist($artistId);
			return;
		}
		if ( $userId = $this->Mmodel->getInt("select id from users where nickname = '$searchTerm'") ) {
			$this->userHome($userId);
			return;
		}
			
		$bands = $this->Mmodel->getRows("select * from bands where name like '%$searchTerm%' order by name limit 30");
		$artists = $this->Mmodel->getRows("select * from artists where name like '%$searchTerm%' order by name limit 30");
		$searchedUsers = $this->Mmodel->getRows("select * from users where nickname like '%$searchTerm%' order by nickname limit 30");
		$this->Mview->showTpl("search.tpl", array(
			"bands" => $bands,
			"artists" => $artists,
			"searchedUsers" => $searchedUsers,
		));
	}
	/*------------------------------------------------------------*/
	public function addBandArtist($bandId, $artistId) {
		$id = $this->Mmodel->getInt("select id from bandArtists where bandId = $bandId and artistId = $artistId");
		if ( $id )
			return($id);
		$id = $this->Mmodel->dbInsert("bandArtists", array("bandId" => $bandId, "artistId" => $artistId));
		$this->updateStats();
		return($id);
	}
	/*------------------------------*/
	public function addArtistToBand() {
		$bandId = $_REQUEST['bandId'];
		if ( $this->validateUser() ) {
			$artistId = $this->getArtist($_REQUEST['artistName']);
			$this->addBandArtist($bandId, $artistId);
			$this->_addArtistToFavorites($this->user['id'], $artistId);
			$this->Mview->assign("currentArtist", $artistId);
		}
		$this->band($bandId);
	}
	/*------------------------------*/
	public function addBandToArtist() {
		$artistId = $_REQUEST['artistId'];
		if ( $this->validateUser() ) {
			$bandId = $this->getBand($_REQUEST['bandName']);
			$this->addBandArtist($bandId, $artistId);
			$this->_addBandToFavorites($this->user['id'], $bandId);
			$this->Mview->assign("currentBand", $bandId);
		}
		$this->artist($artistId);
	}
	/*------------------------------*/
	public function unBandArtist() {
		if ( ! $this->validateUser() ) {
			$this->home();
			return;
		}
		$artistId = $_REQUEST['artistId'];
		$bandId = $_REQUEST['bandId'];
		$page = $_REQUEST['page'];
		$this->Mmodel->_sql("delete from bandArtists where bandId = $bandId and artistId = $artistId");
		if ( $page == 'band' )
			$this->band($bandId);
		else
			$this->artist($artistId);
	}
	/*------------------------------------------------------------*/
	public function deleteBand() {
		if ( ! $this->validateUser() ) {
			$this->home();
			return;
		}
		$bandId = $_REQUEST['bandId'];
		$band = $this->Mmodel->getRow("select * from bands where id = $bandId");
		if ( ! $band ) {
			$this->Mview->error("Band Not Found");
			$this->home();
		}
		$numArtists = $this->Mmodel->getInt("select count(*) from bandArtists where bandId = $bandId");
		// recheck as in the tpl so as to make sure nobody typed in the delete url without the proper credentials
		if ( ( $band['createdBy'] == $this->user['id'] || $this->user['numOps'] > $this->adminNumOps ) && $numArtists == 0 ||
													$this->user['status'] == "Admin" || $this->user['status'] == "superAdmin" ) {
			if ( $numArtists > 0 )
				$this->Mmodel->_sql("delete from bandArtists where bandId = $bandId");
			$this->Mmodel->dbDelete("bands", $bandId);
			$this->Mview->msg("{$band['name']}: Deleted");
		}
		$this->home();
	}
	/*------------------------------------------------------------*/
	public function deleteArtist() {
		if ( ! $this->validateUser() ) {
			$this->home();
			return;
		}
		$artistId = $_REQUEST['artistId'];
		$artist = $this->Mmodel->getRow("select * from artists where id = $artistId");
		if ( ! $artist ) {
			$this->Mview->error("Artist Not Found");
			$this->home();
		}
		$numBands = $this->Mmodel->getInt("select count(*) from bandArtists where artistId = $artistId");
		// recheck as in the tpl so as to make sure nobody typed in the delete url without the proper credentials
		if ( ( $artist['createdBy'] == $this->user['id'] || $this->user['numOps'] > $this->adminNumOps ) && $numBands == 0 ||
													$this->user['status'] == "Admin" || $this->user['status'] == "superAdmin" ) {
			if ( $numBands > 0 )
				$this->Mmodel->_sql("delete from bandArtists where artistId = $artistId");
			$this->Mmodel->dbDelete("artists", $artistId);
			$this->Mview->msg("{$artist['name']}: Deleted");
		}
		$this->home();
	}
	/*------------------------------------------------------------*/
	public function invertStatus() {
		if ( ! $this->validateUser() ) {
			$this->home();
			return;
		}
		$userId = $_REQUEST['userId'];
		$user = $this->Mmodel->getRow("select id, status from users where id = $userId");
		if ( $user['status'] == "superAdmin" ) {
			$this->Mview->error("Can not change superAdmin status");
			$this->home();
			return;
		}
		if ( $user['status'] == "Admin" )
			$status = "";
		else
			$status = "Admin";
		$this->Mmodel->dbUpdate("users", $userId, array("status" => $status, ));
		$this->userHome($userId);
	}
	/*------------------------------------------------------------*/
	public function unFavoriteAll() {
		$rftId = $_SESSION['rftId'];
		$this->Mmodel->sql("delete from favoriteArtists where rftId = $rftId");
		$this->Mmodel->sql("delete from favoriteBands where rftId = $rftId");
		$this->home();
	}
	/*------------------------------------------------------------*/
	public function nickname($rftId) {
		global $Mmodel;
		static $cache = array();
		
		if ( ! $rftId )
			return(""); // not reached || error
		if ( isset($cache[$rftId]) )
			return($cache[$rftId]);
		$nickname = htmlspecialchars($Mmodel->getString("select nickname from users where id = $rftId"));
		if ( ! $nickname )
			$nickname = $rftId;
		$cache[$rftId] = $nickname;
		return($cache[$rftId]);
	}
	/*------------------------------------------------------------*/
}
/*------------------------------------------------------------*/
