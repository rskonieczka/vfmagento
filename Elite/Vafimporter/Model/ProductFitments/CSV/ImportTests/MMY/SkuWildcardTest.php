<?php
class Elite_Vafimporter_Model_ProductFitments_CSV_ImportTests_MMY_SkuWildcardTest extends Elite_Vafimporter_Model_ProductFitments_CSV_ImportTests_TestCase
{    
    protected function doSetUp()
    {
        $this->switchSchema('make,model,year');
        $this->csvData = 'sku, make, model, year
sku*, honda, civic, 2000';
        
        $this->insertProduct('sku1');
        $this->insertProduct('sku2');
        $this->insertProduct('ZZZ');
    }
    
    function testShouldMatchSku1()
    {
        $this->FitmentsImport($this->csvData);
        $fit = $this->getFitForSku('sku1');
        $this->assertEquals( 'honda', $fit->getLevel('make')->getTitle() );
    }
    
    function testShouldMatchSku2()
    {
        $this->FitmentsImport($this->csvData);
        $fit = $this->getFitForSku('sku2');
        $this->assertEquals( 'honda', $fit->getLevel('make')->getTitle() );
    }
    
  
    function testShouldNotMatchZZZ()
    {
        $this->FitmentsImport($this->csvData);
        $fit = $this->getFitForSku('ZZZ');
        $this->assertFalse( $fit );
    }
    
  
}