<?php
/*------------------------------------------------------------*/
class Rft extends Mcontroller {
	/*------------------------------------------------------------*/
	private $rftId;
	private $user;
	private $numsTtl;
	/*------------------------------------------------------------*/
	public function __construct() {
		parent::__construct();
		$this->numsTtl = 30*60;
		$this->Mview->register_modifier('nickname', array($this, 'nickname',));
	}
	/*------------------------------------------------------------*/
	protected function before() {
		parent::before();
		$this->setUser();
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
		$searchWords = array_slice($words,0,  4);
		$searchWordList = implode(" ", $searchWords);
		$this->Mview->assign("searchQuery", "wikipedia +\"$title\" $searchWordList");
	}
	/*------------------------------------------------------------*/
	private function _addArtistToFavorites($artistId) {
		$rftId = $this->rftId;
		$sql = "select count(*) from favoriteArtists where rftId = $rftId and artistId = $artistId";
		$is = $this->Mmodel->getInt($sql);
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
		$rftId = $this->rftId;
		$artistId = $_REQUEST['artistId'];
		$this->_addArtistToFavorites($artistId);
		$this->redir();
	}
	/*------------------------------*/
	public function removeFavoriteArtist() {
		$ok = @$_REQUEST['ok'];
		if ( $ok != "on" ) {
			$this->Mview->msgLater("removeFavoriteArtist: box not checked. ignoring.");
			$this->redir();
			return;
		}
		$rftId = $this->rftId;
		$artistId = $_REQUEST['artistId'];
		$sql = "delete from favoriteArtists where rftId = $rftId and artistId = $artistId";
		$this->Mmodel->sql($sql);
		$this->redir();
	}
	/*------------------------------*/
	public function removeFavoriteBand() {
		$ok = @$_REQUEST['ok'];
		if ( $ok != "on" ) {
			$this->Mview->msgLater("removeFavoriteBand: box not checked. ignoring.");
			$this->redir();
			return;
		}
		$rftId = $this->rftId;
		$bandId = $_REQUEST['bandId'];
		$sql = "delete from favoriteBands where rftId = $rftId and bandId = $bandId";
		$this->Mmodel->sql($sql);
		$this->redir();
	}
	/*------------------------------*/
	private function _addBandToFavorites($bandId) {
		$rftId = $this->rftId;
		$sql = "select count(*) from favoriteBands where rftId = $rftId and bandId = $bandId";
		$is = $this->Mmodel->getInt($sql);
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
		$rftId = $this->rftId;
		$bandId = $_REQUEST['bandId'];
		$this->_addBandToFavorites($bandId);
		$this->redir();
	}
	/*------------------------------------------------------------*/
	private function validateUser() {
		if ( $this->rftId && $this->rftId == @$this->user['id'] )
			return(true);

		 $rftId = $this->Mmodel->dbInsert("users", array(
						"passwd" => "".rand(123456, 987654),
		 				"created" => date("Y-m-d"),
				));
		 $this->setUser($rftId);
		 return(true);
	}
	/*----------------------------------------*/
	private function setUser($rftId = null) {
		if ( $rftId ) {
			$this->Mview->setCookie("rftId", $rftId);
			$this->rftId = $rftId;
		} elseif ( isset($_COOKIE['rftId']) ) {
			$this->rftId = $_COOKIE['rftId'];;
		} else {
			error_log::error("setUser: rftId not set");
			return;
		}
		$rftId = $this->rftId;
		if ( $this->user && $this->user['id'] == $rftId )
			return;
		$this->user = $this->Mmodel->getById("users", $rftId);
		if ( ! $this->user ) {
			return;
		}
		if ( ! $this->user['nickname'] )
			$this->user['nickname'] = $rftId;

		$this->Mview->assign("user", $this->user);
		$this->Mview->assign("rftId", $this->rftId);
	}
	/*------------------------------------------------------------*/
	public function index() {
		$this->home();
	}
	/*------------------------------------------------------------*/
	private function visitorHome() {
		$bands = $this->Mmodel->getRows("select * from bands order by name limit 20");
		$this->ammendBands($bands);
		$artists = $this->Mmodel->getRows("select * from artists order by name limit 20");
		$this->ammendArtists($bands);
		$this->setTitle("Visitor");
		$this->Mview->showTpl("home.tpl", array(
			'bands' => $bands,
			'artists' => $artists,
		));
	}
	/*----------------------------------------*/
	private function userBands($rftId) {
		$favSql = "select bandId from favoriteBands where rftId = $rftId";
		$favoriteIds = $this->Mmodel->getStrings($favSql);
		if ( $favoriteIds )
			$favorites = $this->Mmodel->getRows("select * from bands where id in ( $favSql )");
		else
			$favorites = array();
		$userLatest = $this->Mmodel->getRows("select * from bands where createdBy = $rftId order by id desc limit 10");
		$latest = $this->Mmodel->getRows("select * from bands where createdBy != $rftId order by id desc limit 10");
		$userBands = array();
		foreach ( $favorites as $band ) {
			$band['isFavoraite'] = true;
			$bandId = $band['id'];
			$userBands[$bandId] = $band;
		}
		foreach ( $userLatest as $band ) {
			$bandId = $band['id'];
			if ( ! @$userBands[$bandId] )
				$userBands[$bandId] = $band;
		}
		foreach ( $latest as $band ) {
			$bandId = $band['id'];
			if ( ! @$userBands[$bandId] )
				$userBands[$bandId] = $band;
		}
		$userBands = array_values($userBands);
		usort($userBands, array($this, "byName"));
		$this->ammendBands($userBands);
		return($userBands);
	}
	/*------------------------------*/
	private function byName($a, $b) {
		return(strcmp($a['name'], $b['name']));
	}
	/*------------------------------*/
	private function userArtists($rftId) {
		$favSql = "select artistId from favoriteArtists where rftId = $rftId";
		$favoriteIds = $this->Mmodel->getStrings($favSql);
		if ( $favoriteIds )
			$favorites = $this->Mmodel->getRows("select * from artists where id in ( $favSql )");
		else
			$favorites = array();
		$userLatest = $this->Mmodel->getRows("select * from artists where createdBy = $rftId order by id desc limit 10");
		$latest = $this->Mmodel->getRows("select * from artists where createdBy != $rftId order by id desc limit 10");
		$userArtists = array();
		foreach ( $favorites as $artist ) {
			$artist['isFavoraite'] = true;
			$artistId = $artist['id'];
			$userArtists[$artistId] = $artist;
		}
		foreach ( $userLatest as $artist ) {
			$artistId = $artist['id'];
			if ( ! @$userArtists[$artistId] )
				$userArtists[$artistId] = $artist;
		}
		foreach ( $latest as $artist ) {
			$artistId = $artist['id'];
			if ( ! @$userArtists[$artistId] )
				$userArtists[$artistId] = $artist;
		}
		$userArtists = array_values($userArtists);
		usort($userArtists, array($this, "byName"));
		$this->ammendArtists($userArtists);
		return($userArtists);
	}
	/*------------------------------*/
	public function userHome($rftId = null) {
		if ( ! $rftId ) {
			if ( @$_REQUEST['userId'] )
				$rftId = $_REQUEST['userId'];
			elseif ( $this->rftId )
				$rftId = $this->rftId;
		} else {
			$this->Mview->msgLater("userHome: No userId");
			$this->visitorHome();
			return;
		}
		if ( $this->user && $this->user['id'] == $rftId ) {
			$homeUser = $this->user;
		} else {
			$homeUser = $this->Mmodel->getById("users", $rftId);
			if ( ! $homeUser['nickname'] )
				$homeUser['nickname'] = $homeUser['id'];
		}
		$this->setTitle("{$homeUser['nickname']}'s home");
		$bands = $this->userBands($rftId);
		$artists = $this->userArtists($rftId);
		$this->Mview->showtpl("home.tpl", array(
			'homeUser' => $homeUser,
			'bands' => $bands,
			'artists' => $artists,
		));
		return(true);
	}
	/*------------------------------*/
	public function home() {
		if ( $this->rftId )
			$this->userHome();
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
		$band = $this->Mmodel->getById("bands", $bandId);
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
		$artist = $this->Mmodel->getById("artists", $artistId);
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
			$this->redir2band($bandId);
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
			$this->redir2artist($artistId);
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
		$this->_addBandToFavorites($bandId);
		$this->redir2band($bandId);
	}
	/*------------------------------------------------------------*/
	public function addArtist() {
		if ( ! $this->validateUser() ) {
			$this->redir();
			return;
		}
		$artistId = $this->getArtist($_REQUEST['artistName']);
		$this->_addArtistToFavorites($artistId);
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
		if ( ! $this->validateUser() ) {
			$this->redir2band($bandId);
			return;
		}
		$artistId = $this->getArtist($_REQUEST['artistName']);
		$this->addBandArtist($bandId, $artistId);
		$this->_addBandToFavorites($bandId);
		$this->_addArtistToFavorites($artistId);
		$this->redir2band($bandId);
	}
	/*------------------------------*/
	public function addBandToArtist() {
		$artistId = $_REQUEST['artistId'];
		if ( ! $this->validateUser() ) {
			$this->redir2artist($artistId);
			return;
		}
		$bandId = $this->getBand($_REQUEST['bandName']);
		$this->addBandArtist($bandId, $artistId);
		$this->_addArtistToFavorites($artistId);
		$this->_addBandToFavorites($bandId);
		$this->redir2artist($artistId);
	}
	/*------------------------------*/
	public function unBandArtist() {
		if ( ! $this->validateUser() ) {
			$this->redir();
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
			$this->redir();
			return;
		}
		$rftId = $this->rftId;
		$newNickname = $_REQUEST['nickname'];
		$newNickname = preg_replace('/\s+/', ' ', $newNickname);
		$newNickname = preg_replace("/[^A-Za-z0-9 ]*/", "", $newNickname);
		$newNickname = ucwords($newNickname);
		$newNickname = trim($newNickname);
		if ( $newNickname ) {
			$this->Mmodel->dbUpdate("users", $rftId, array("nickname" => $newNickname,));
			$this->user['nickname'] = $newNickname;
		}
		$this->redir();
	}
	/*------------------------------------------------------------*/
	public function deleteBand() {
		$userId = $this->user['id'];
		$bandId = $_REQUEST['bandId'];
		if ( ! $this->validateUser() ) {
			$this->redir2band($bandId);
			return;
		}
		$band = $this->Mmodel->getById("bands", $bandId);
		if ( ! $band ) {
			$this->Mview->msgLater("Band Not Found");
			$this->redir();
			return;
		}
		$bandName = $band['name'];
		if ( $band['createdBy'] != $userId ) {
			$nickname = $this->user['nickname'];
			$this->Mview->msgLater("deleteBand: You ($nickname) are not the creator of $bandName");
			$this->redir2band($bandId);
			return;
		}
		$numArtists = $this->numArtists($bandId, true);
		if ( $numArtists > 0 ) {
			$s = $numArtists == 1 ? "" : "s";
			$this->Mview->msgLater("deleteBand: $bandName: un-tie $numArtist$s musicians first");
			$this->redir2band($bandId);
			return;
		}
		$ok = @$_REQUEST['ok'];
		if ( $ok != "on" ) {
			$this->Mview->msgLater("deleteBand: box not checked. ignoring.");
			$this->redir2band($bandId);
			return;
		}
		$this->Mmodel->dbDelete("bands", $bandId);
		$this->Mview->msgLater("$bandName: Deleted");
		$this->redir();
	}
	/*------------------------------------------------------------*/
	public function deleteArtist() {
		$userId = $this->user['id'];
		$artistId = $_REQUEST['artistId'];
		if ( ! $this->validateUser() ) {
			$this->redir2artist($artistId);
			return;
		}
		$artist = $this->Mmodel->getById("artists", $artistId);
		if ( ! $artist ) {
			$this->Mview->msgLater("Artist Not Found");
			$this->redir();
		}
		$artistName = $artist['name'];
		if ( $artist['createdBy'] != $userId ) {
			$nickname = $this->user['nickname'];
			$this->Mview->msgLater("deleteArtist: You ($nickname) are not the creator of $artistName");
			$this->redir2artist($artistId);
			return;
		}
		$numBands = $this->numBands($artistId, true);
		if ( $numBands > 0 ) {
			$s = $numBands == 1 ? "" : "s";
			$this->Mview->msgLater("deleteArtist: $bandName: un-tie $numBands band$s first");
			$this->redir2band($bandId);
			return;
		}
		$ok = @$_REQUEST['ok'];
		if ( $ok != "on" ) {
			$this->Mview->msgLater("deleteArtist: box not checked. ignoring.");
			$this->redir2band($bandId);
			return;
		}
		$this->Mmodel->dbDelete("artists", $artistId);
		$this->Mview->msgLater("$artistName: Deleted");
		$this->redir();
	}
	/*------------------------------------------------------------*/
	public function unFavoriteAllBands() {
		$ok = @$_REQUEST['ok'];
		if ( $ok != "on" ) {
			$this->Mview->msgLater("unFavoriteAllBands: box not checked. ignoring.");
			$this->redir();
			return;
		}
		$rftId = $this->user['id'];
		$this->Mmodel->sql("delete from favoriteBands where rftId = $rftId");
		$this->redir();
	}
	/*------------------------------------------------------------*/
	public function unFavoriteAllArtists() {
		$ok = @$_REQUEST['ok'];
		if ( $ok != "on" ) {
			$this->Mview->msgLater("unFavoriteAllArtists: box not checked. ignoring.");
			$this->redir();
			return;
		}
		$rftId = $this->user['id'];
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
			"createdOn" => date("Y-m-d"),
			"createdBy" => $this->user['id'],
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
			"createdOn" => date("Y-m-d"),
			"createdBy" => $this->rftId,
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
	private function ammendArtists(&$artists) {
		foreach ( $artists as $key => $artist ) {
			$artists[$key]['numBands'] = $this->numBands($artist['id']);
		}
	}
	/*------------------------------------------------------------*/
	private function ammendBands(&$bands) {
		foreach ( $bands as $key => $band ) {
			$bands[$key]['numArtists'] = $this->numArtists($band['id']);
		}
	}
	/*------------------------------------------------------------*/
	private function numArtists($bandId, $noCache = false) {
		$sql =  "select count(*) from bandArtists where bandId = $bandId";
		$numArtists = $this->Mmodel->getInt($sql, $noCache ? null : $this->numsTtl);
		return($numArtists);
	}
	/*------------------------------------------------------------*/
	private function numBands($artistId, $noCache = false) {
		$sql = "select count(*) from bandArtists where artistId = $artistId";
		$numBands = $this->Mmodel->getInt($sql, $noCache ? null : $this->numsTtl);
		return($numBands);
	}
	/*------------------------------------------------------------*/
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
