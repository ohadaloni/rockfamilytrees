<?php
/*------------------------------------------------------------*/
class Rft extends Mcontroller {
	/*------------------------------------------------------------*/
	private $user = null;
	/*------------------------------------------------------------*/
	public function __construct() {
		parent::__construct();
		$this->setUser();
		$this->Mview->register_modifier('nickname', array($this, 'nickname',));
	}
	/*------------------------------------------------------------*/
	protected function before() {
		parent::before();
		header('Content-type: text/html; charset=UTF-8');
		$this->Mview->showTpl("header.tpl");
		$this->Mview->showMsgs();
	}
	/*------------------------------------------------------------*/
	protected function after() {
		parent::after();
		$this->Mview->showTpl("footer.tpl");
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
		$this->redir();
	}
	/*------------------------------*/
	public function removeFavoriteArtist() {
		$ok = @$_REQUEST['ok'];
		if ( $ok != "on" ) {
			$this->Mview->msgLater("removeFavoriteArtist: box not checked. ignoring.");
			$this->redirect("/rft/home");
			return;
		}
		$rftId = $_SESSION['rftId'];
		$artistId = $_REQUEST['artistId'];
		$sql = "delete from favoriteArtists where rftId = $rftId and artistId = $artistId";
		$this->Mmodel->sql($sql);
		$ind = array_search($artistId, $this->user['favoriteArtists']);
		unset($this->user['favoriteArtists'][$ind]);
		$this->Mview->assign("user", $this->user);
		$this->redir();
	}
	/*------------------------------*/
	public function removeFavoriteBand() {
		$ok = @$_REQUEST['ok'];
		if ( $ok != "on" ) {
			$this->Mview->msgLater("removeFavoriteBand: box not checked. ignoring.");
			$this->redirect("/rft/home");
			return;
		}
		$rftId = $_SESSION['rftId'];
		$bandId = $_REQUEST['bandId'];
		$sql = "delete from favoriteBands where rftId = $rftId and bandId = $bandId";
		$this->Mmodel->sql($sql);
		$ind = array_search($bandId, $this->user['favoriteBands']);
		unset($this->user['favoriteBands'][$ind]);
		$this->Mview->assign("user", $this->user);
		$this->redir();
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
		$this->redir();
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
		if ( isset($_SESSION['rftId']) &&
				isset($this->user['id']) &&
					$_SESSION['rftId'] == $this->user['id'] )
			return(true);

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
			return;
		}

		if ( isset($_COOKIE['rftId']) && $this->loadUser($_COOKIE['rftId']) ) {
			$_SESSION['rftId'] = $_COOKIE['rftId'];
		}
	}
	/*------------------------------------------------------------*/
	public function index() {
		$this->home();
	}
	/*------------------------------------------------------------*/
	private function visitorHome() {
		$bands = $this->Mmodel->getRows("select * from bands order by name limit 20");
		$artists = $this->Mmodel->getRows("select * from artists order by name limit 20");
		$this->setTitle("Visitor");
		$this->Mview->showTpl("home.tpl", array(
			'bands' => $bands,
			'artists' => $artists,
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
	public function switchId() {
		$newRftId = trim($_REQUEST['nickname']);
		$passwd = trim($_REQUEST['passwd']);
		if ( ! $newRftId || ! $passwd ) {
			$this->redir();
			return;
		}
		$dbPasswd = $this->Mmodel->getString("select passwd from users where id = $newRftId");
		if ( $dbPasswd != $passwd ) {
			$this->Mview->msgLater("Switch User Failed");
			$this->redir();
			return;
		}
		$this->setUser($newRftId);
		$this->redir();

	}
	/*------------------------------------------------------------*/
	public function band($bandId = null) {
		if ( ! $bandId )
			$bandId = $_REQUEST['bandId'];
		$band = $this->Mmodel->getRow("select * from bands where id = $bandId");
		$this->setTitle($band['name']);
		$artists = $this->Mmodel->getRows("select a.* from artists a, bandArtists ba where a.id = ba.artistId and ba.bandId = $bandId order by a.name");
		$artistNames = Mutils::arrayColumn($artists, "name");
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
		$bandNames =  Mutils::arrayColumn($bands, "name");
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
			$this->Mview->msgLater("$canonical: No change");
		elseif ( $is )
			$this->Mview->msgLater("$canonical already exists");
		else
			$this->Mmodel->dbUpdate("bands", $bandId, array("name" => $canonical));
		$this->redir2band($bandId);
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
			$this->Mview->msgLater("$canonical already exists");
		else
			$this->Mmodel->dbUpdate("artists", $artistId, array("name" => $canonical));
		$this->redir2artist($artistId);
	}
	/*------------------------------------------------------------*/
	public function addBand() {
		if ( ! $this->validateUser() ) {
			$this->redir();
			return;
		}

		$bandId = $this->getBand($_REQUEST['bandName']);
		$this->_addBandToFavorites($this->user['id'], $bandId);
		$this->redir2band($bandId);
	}
	/*------------------------------------------------------------*/
	public function addArtist() {
		if ( ! $this->validateUser() ) {
			$this->redir();
			return;
		}
		$artistId = $this->getArtist($_REQUEST['artistName']);
		$this->_addArtistToFavorites($this->user['id'], $artistId);
		$this->redir2artist($artistId);
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
	public function addArtistToBand() {
		$bandId = $_REQUEST['bandId'];
		if ( $this->validateUser() ) {
			$artistId = $this->getArtist($_REQUEST['artistName']);
			$this->addBandArtist($bandId, $artistId);
			$this->_addArtistToFavorites($this->user['id'], $artistId);
			$this->Mview->assign("currentArtist", $artistId);
		}
		$this->redir2band($bandId);
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
		$this->redir2artist($artistId);
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
		$ok = @$_REQUEST['ok'];
		if ( $ok == "on" ) {
			$conds = "bandId = $bandId and artistId = $artistId";
			$sql = "delete from bandArtists where $conds";
			$this->Mmodel->sql($sql);
		} else {
			$this->Mview->msgLater("unBandArtist: box not checked. ignoring.");
		}
		if ( $page == 'band' )
			$this->redir2band($bandId);
		else
			$this->redir2artist($artistId);
	}
	/*------------------------------------------------------------*/
	public function changeNickname() {
		$ok = @$_REQUEST['ok'];
		if ( $ok != "on" ) {
			$this->Mview->msgLater("changeNickname: box not checked. ignoring.");
			$this->redirect("/rft/home");
			return;
		}
		$rftId = $_SESSION['rftId'];
		$nickname = trim(ucwords(preg_replace('/\s+/', ' ', $_REQUEST['nickname'])));
		if ( $nickname ) {
			$this->Mmodel->dbUpdate("users", $rftId, array("nickname" => $nickname,));
			$this->user['nickname'] = $nickname;
			$this->Mview->assign("user", $this->user);
		}
		$this->redir();
	}
	/*------------------------------------------------------------*/
	public function deleteBand() {
		if ( ! $this->validateUser() ) {
			$this->redir();
			return;
		}
		$bandId = $_REQUEST['bandId'];
		$ok = @$_REQUEST['ok'];
		if ( $ok != "on" ) {
			$this->Mview->msgLater("deleteBand: box not checked. ignoring.");
			$this->redirect("/rft/band?bandId=$bandId");
			return;
		}
		$band = $this->Mmodel->getRow("select * from bands where id = $bandId");
		if ( ! $band ) {
			$this->Mview->msgLater("Band Not Found");
			$this->redir();
		}
		$numArtists = $this->Mmodel->getInt("select count(*) from bandArtists where bandId = $bandId");
		if ( $band['createdBy'] == $this->user['id'] && $numArtists == 0 ) {
			if ( $numArtists > 0 )
				$this->Mmodel->sql("delete from bandArtists where bandId = $bandId");
			$this->Mmodel->dbDelete("bands", $bandId);
			$this->Mview->msgLater("{$band['name']}: Deleted");
		}
		$this->redir();
	}
	/*------------------------------------------------------------*/
	public function deleteArtist() {
		if ( ! $this->validateUser() ) {
			$this->home();
			return;
		}
		$artistId = $_REQUEST['artistId'];
		$ok = @$_REQUEST['ok'];
		if ( $ok != "on" ) {
			$this->Mview->msgLater("deleteArtist: box not checked. ignoring.");
			$this->redirect("/rft/artist?artistId=$artistId");
			return;
		}
		$artist = $this->Mmodel->getRow("select * from artists where id = $artistId");
		if ( ! $artist ) {
			$this->Mview->msgLater("Artist Not Found");
			$this->home();
		}
		$numBands = $this->Mmodel->getInt("select count(*) from bandArtists where artistId = $artistId");
		if ( $artist['createdBy'] == $this->user['id'] && $numBands == 0 ) {
			if ( $numBands > 0 )
				$this->Mmodel->sql("delete from bandArtists where artistId = $artistId");
			$this->Mmodel->dbDelete("artists", $artistId);
			$this->Mview->msgLater("{$artist['name']}: Deleted");
		}
		$this->redir();
	}
	/*------------------------------------------------------------*/
	public function unFavoriteAllBands() {
		$ok = @$_REQUEST['ok'];
		if ( $ok != "on" ) {
			$this->Mview->msgLater("unFavoriteAllBands: box not checked. ignoring.");
			$this->home();
			return;
		}
		$rftId = $_SESSION['rftId'];
		$this->Mmodel->sql("delete from favoriteBands where rftId = $rftId");
		$this->redir();
	}
	/*------------------------------------------------------------*/
	public function unFavoriteAllArtists() {
		$ok = @$_REQUEST['ok'];
		if ( $ok != "on" ) {
			$this->Mview->msgLater("unFavoriteAllArtists: box not checked. ignoring.");
			$this->home();
			return;
		}
		$rftId = $_SESSION['rftId'];
		$this->Mmodel->sql("delete from favoriteArtists where rftId = $rftId");
		$this->redir();
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
		return($ret);
	}
	/*------------------------------------------------------------*/
	private function addBandArtist($bandId, $artistId) {
		$id = $this->Mmodel->getInt("select id from bandArtists where bandId = $bandId and artistId = $artistId");
		if ( $id )
			return($id);
		$id = $this->Mmodel->dbInsert("bandArtists", array("bandId" => $bandId, "artistId" => $artistId));
		return($id);
	}
	/*------------------------------------------------------------*/
	// smarty plugin must be public
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
	private function redir2artist($artistId) {
		$this->redirect("/rft/artist?artistId=$artistId");
	}
	/*------------------------------------------------------------*/
	private function redir2band($bandId) {
		$this->redirect("/rft/band?bandId=$bandId");
	}
	/*------------------------------------------------------------*/
	private function redir() {
		$this->redirect("/rft/home");
	}
	/*------------------------------------------------------------*/
}
/*------------------------------------------------------------*/
