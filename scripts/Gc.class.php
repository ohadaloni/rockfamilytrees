<?php
/*------------------------------------------------------------*/
class Gc extends Mcontroller {
	/*------------------------------------------------------------*/
	public function index() {
		// bands and artists with no name
		$conds = "name = '' or name is null";
		foreach ( array('bands', 'artists', ) as $table ) {
			$sql = "select * from $table where $conds";
			$rows =  $this->Mmodel->getRows($sql);
			if ( $rows ) {
				echo "$table - empty names\n";
				Mview::print_r($rows, "rows", basename(__FILE__), __LINE__, null, false);
			}
		}
		// bands with no members
		$sql = "select * from bands left join bandArtists on bands.id = bandArtists.bandId where bandArtists.artistId is null";
		echo "$sql;\n";
		$rows =  $this->Mmodel->getRows($sql);
			if ( $rows ) {
				echo "bands with no members\n";
				Mview::print_r($rows, "rows", basename(__FILE__), __LINE__, null, false);
			}

		// bands with 1 member.
		$sql = "select bandId,count(*) from bands, bandArtists where bands.id = bandArtists.bandId group by bands.id having count(*) = 1";
		$rows =  $this->Mmodel->getRows($sql);
			if ( $rows ) {
				echo "bands with one members\n";
				$ids = Mutils::arrayColumn($rows, "bandId");
				$inlist = implode(", ", $ids);
				$sql = "select * from bands where id in ( $inlist) order by name";
				$rows =  $this->Mmodel->getRows($sql);
				Mview::print_r($rows, "rows", basename(__FILE__), __LINE__, null, false);
			}

	}
	/*------------------------------------------------------------*/
	/*------------------------------------------------------------*/
}
