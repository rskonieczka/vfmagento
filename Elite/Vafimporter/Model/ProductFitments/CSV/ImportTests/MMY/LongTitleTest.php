<?php
class Elite_Vafimporter_Model_ProductFitments_CSV_ImportTests_MMY_LongTitleTest extends Elite_Vafimporter_Model_ProductFitments_CSV_ImportTests_TestCase
{    
    protected function doSetUp()
    {
        $this->switchSchema('make,model,year');
        $this->csvData = 'sku, make, model, year
sku, honda, 7.3L V8 Powerstroke Diesel W Dual Alternator Top Alternator, 2000';
        
        $this->insertProduct( self::SKU );
    }
    
    function testSku()
    {
        $this->FitmentsImport($this->csvData);
        $fit = $this->getFitForSku('sku');
        $this->assertEquals( '7.3L V8 Powerstroke Diesel W Dual Alternator Top Alternator', $fit->getLevel( 'model' )->getTitle() );
    }

    
}