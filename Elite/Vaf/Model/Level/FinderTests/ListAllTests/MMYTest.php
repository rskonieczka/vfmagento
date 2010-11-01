<?php
class Elite_Vaf_Model_Level_FinderTests_ListAllTests_MMYTest extends Elite_Vaf_TestCase
{
    
    protected function doSetUp()
    {
        $this->switchSchema('make,model,year');
    }
    
    function testListAll_OnLevel()
    {
        $vehicle1 = $this->createMMY( 'A', 'A', '1' ); 
        $vehicle2 = $this->createMMY( 'A', 'B', '1' );
        $vehicle3 = $this->createMMY( 'A', 'C', '1' );
        
        $this->insertFitmentMMY( $vehicle1 );
        $this->insertFitmentMMY( $vehicle2 );
        $this->insertFitmentMMY( $vehicle3 );
        
        $model = new Elite_Vaf_Model_Level('model');
        $actual = $model->listAll();
        $this->assertEquals( 'A', $actual[0]->getTitle(), 'should sort items' );
        $this->assertEquals( 'B', $actual[1]->getTitle(), 'should sort items' );
        $this->assertEquals( 'C', $actual[2]->getTitle(), 'should sort items' );
    }
    
    function testListAll_OnFinder()
    {
        $vehicle1 = $this->createMMY( 'A', 'A', '1' ); 
        $vehicle2 = $this->createMMY( 'A', 'B', '1' );
        $vehicle3 = $this->createMMY( 'A', 'C', '1' );
        
        $this->insertFitmentMMY( $vehicle1 );
        $this->insertFitmentMMY( $vehicle2 );
        $this->insertFitmentMMY( $vehicle3 );
        
        $actual = $this->levelFinder()->listAll('model');
        $this->assertEquals( 'A', $actual[0]->getTitle(), 'should sort items' );
        $this->assertEquals( 'B', $actual[1]->getTitle(), 'should sort items' );
        $this->assertEquals( 'C', $actual[2]->getTitle(), 'should sort items' );
    }
    
    function testListAllIsRepeatable()
    {
        $vehicle1 = $this->createMMY( 'A', 'A', '1' ); 
        $vehicle2 = $this->createMMY( 'A', 'B', '1' );
        $vehicle3 = $this->createMMY( 'A', 'C', '1' );
        
        $this->insertFitmentMMY( $vehicle1 );
        $this->insertFitmentMMY( $vehicle2 );
        $this->insertFitmentMMY( $vehicle3 );
        
        $model = new Elite_Vaf_Model_Level('model');
        $actual = $model->listAll();
        $actual = $model->listAll();
        $this->assertEquals( 'A', $actual[0]->getTitle(), 'should sort items' );
        $this->assertEquals( 'B', $actual[1]->getTitle(), 'should sort items' );
        $this->assertEquals( 'C', $actual[2]->getTitle(), 'should sort items' );
    }
    
    function testListAllWithParentId()
    {
        $vehicle1 = $this->createMMY( 'A', 'A', '1' ); 
        $vehicle2 = $this->createMMY( 'A', 'B', '1' );
        $vehicle3 = $this->createMMY( 'B', 'Z', 'Z' );
        
        $this->insertFitmentMMY( $vehicle1 );
        $this->insertFitmentMMY( $vehicle2 );
        $this->insertFitmentMMY( $vehicle3 );
        
        $model = new Elite_Vaf_Model_Level('model');
        $actual = $model->listAll($vehicle1->getLevel('make')->getId());
        $this->assertEquals( 'A', $actual[0]->getTitle(), 'should sort items' );
        $this->assertEquals( 'B', $actual[1]->getTitle(), 'should sort items' );
    }  
    
}
