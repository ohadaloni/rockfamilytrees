<?php
/*------------------------------------------------------------*/
class TheDups extends Mcontroller {
	/*------------------------------------------------------------*/
	public function index() {
		$sql = "select * from bands where name like 'the %'";
		$theBands = $this->Mmodel->getRows($sql);
		$sql = "select * from bands where name not like 'the %'";
		$notTheBands = $this->Mmodel->getRows($sql);
		$notTheBands = Mutils::reIndexBy($notTheBands, "name");
		foreach ( $theBands as $theBand ) {
			$notTheName = substr($theBand['name'], 4);
			$notTheBand = @$notTheBands[$notTheName];
			if ( ! $notTheBand )
				continue;
			$theBandId = $theBand['id'];
			$notTheBandId = $notTheBand['id'];
			if ( $notTheName == 'Family' ) {
				/*	echo "Indeed there are two bands, 'Family' and 'The Family'\n";	*/
				continue;
			}
			echo "The $notTheName: $theBandId, $notTheBandId\n";
		}

	}
	/*------------------------------------------------------------*/
	/*------------------------------------------------------------*/
}
