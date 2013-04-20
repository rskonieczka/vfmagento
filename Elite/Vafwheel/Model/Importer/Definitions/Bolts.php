<?php
class Elite_Vafwheel_Model_Importer_Definitions_Bolts extends VF_Import_VehiclesList_CSV_Import
{
    function importRow($row)
    {   
        $this->row_number++;
        $this->oldImportRow($row);
    }
    
    /**
    * @param array $row
    * @param VF_Vehicle|boolean the vehicle, false if none (for example, when setting a product as universal)
    */
    function doImportRow( $row, $vehicle )
    {
        $boltPattern = $this->getFieldValue( 'bolt_pattern', $row );
        $offset = $this->getFieldValue( 'offset', $row );
        $boltPattern = Elite_Vafwheel_Model_BoltPattern::create($boltPattern, $offset);
        
        $wheelDefinition = new Elite_Vafwheel_Model_Vehicle($vehicle);
        $wheelDefinition->addBoltPattern($boltPattern);
    }
 
}