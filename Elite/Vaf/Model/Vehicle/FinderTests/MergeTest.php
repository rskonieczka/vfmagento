<?php
class Elite_Vaf_Model_Vehicle_FinderTests_MergeTest extends Elite_Vaf_TestCase
{
    function doSetUp()
    {
        $this->switchSchema('make,model,year');
    }
    
    // @todo what about a way to merge a make with a model, or year (by traversing the level closest to the root level, and "blowing out" all applicable vehicles).
    
    /**
    * @expectedException Elite_Vaf_Model_Vehicle_Finder_Exception_DifferingGrain 
    */
    function testShouldNotAllowDifferingSlaveLevels()
    {
        $vehicle1 = $this->createMMY('Honda','Civic','2000');
        $vehicle2 = $this->createMMY('Honda','Civic','2001');
        
        $slaveLevels = array(
            array('year', $vehicle1 ),
            array('model', $vehicle2 ),
        );
        $masterLevel = array('year', $vehicle2 );
        
        $this->levelFinder()->merge( $slaveLevels, $masterLevel );
    }
    
    /**
    * @expectedException Elite_Vaf_Model_Vehicle_Finder_Exception_DifferingGrain 
    */
    function testShouldNotAllowDifferingMasterLevel()
    {
        $vehicle1 = $this->createMMY('Honda','Civic','2000');
        $vehicle2 = $this->createMMY('Honda','Civic','2001');
        
        $slaveLevels = array(
            array('model', $vehicle1 ),
            array('model', $vehicle2 ),
        );
        $masterLevel = array('year', $vehicle2 );
        
        $this->levelFinder()->merge( $slaveLevels, $masterLevel );
    }
    
    function testShouldMergeYear()
    {
        $vehicle1 = $this->createMMY('Honda','Civic','2000');
        $vehicle2 = $this->createMMY('Honda','Civic','2001');
        
        $slaveLevels = array(
            array('year', $vehicle1 ),
            array('year', $vehicle2 ),
        );
        $masterLevel = array('year', $vehicle2 );
        
        $this->levelFinder()->merge( $slaveLevels, $masterLevel );
        
        $this->assertTrue( $this->vehicleExists(array('make'=>'Honda','model'=>'Civic','year'=>2001)) );
        $this->assertFalse( $this->vehicleExists(array('make'=>'Honda','model'=>'Civic','year'=>2000)), 'should delete slave vehicle');
    }
    
    function testShouldMergeModel()
    {
        $vehicle1 = $this->createMMY('Honda','Civic','2000');
        $vehicle2 = $this->createMMY('Honda','Accord','2001');
        
        $slaveLevels = array(
            array('model', $vehicle1 ),
            array('model', $vehicle2 ),
        );
        $masterLevel = array('model', $vehicle2 );
        $this->levelFinder()->merge( $slaveLevels, $masterLevel );
        
        $this->assertTrue( $this->vehicleExists(array('make'=>'Honda','model'=>'Accord')) );
        $this->assertFalse( $this->vehicleExists(array('make'=>'Honda','model'=>'Civic')), 'should delete slave vehicle' );
    }
    
    function testShouldMergeYears_WhenMergeModel()
    {
        $vehicle1 = $this->createMMY('Honda','Civic','2000');
        $vehicle2 = $this->createMMY('Honda','Accord','2001');
        $vehicle3 = $this->createMMY('Honda','Civic','2002');
        
        $slaveLevels = array(
            array('model', $vehicle1 ),
            array('model', $vehicle2 ),
        );
        $masterLevel = array('model', $vehicle2 );

        $this->levelFinder()->merge( $slaveLevels, $masterLevel );
        
        $this->assertTrue( $this->vehicleExists(array('make'=>'Honda','model'=>'Accord','year'=>2000)) );
        $this->assertTrue( $this->vehicleExists(array('make'=>'Honda','model'=>'Accord','year'=>2001)) );
        $this->assertTrue( $this->vehicleExists(array('make'=>'Honda','model'=>'Accord','year'=>2002)) );
        
        $this->assertFalse( $this->vehicleExists(array('make'=>'Honda','model'=>'Civic')) );
    }
    
    function testShouldMergeModels_WhenMergeMakes()
    {
        $vehicle1 = $this->createMMY('Honda','Civic','2000');
        $vehicle2 = $this->createMMY('Honda-oops','Civic','2000');
        $vehicle3 = $this->createMMY('Honda-oops','Civic','2001');
        
        $slaveLevels = array(
            array('make', $vehicle1 ),
            array('make', $vehicle2 ),
        );
        $masterLevel = array('make', $vehicle1 );        
        $this->levelFinder()->merge( $slaveLevels, $masterLevel );
        
        $this->assertTrue( $this->vehicleExists(array('make'=>'Honda','model'=>'Civic','year'=>2000)) );        
        $this->assertTrue( $this->vehicleExists(array('make'=>'Honda','model'=>'Civic','year'=>2001)) );        
        $this->assertFalse( $this->vehicleExists(array('make'=>'Honda-oops')) );
    }
    
    function testShouldMergeYears_WhenMergeMake()
    {
        $vehicle1 = $this->createMMY('Honda','Civic','2000');
        $vehicle2 = $this->createMMY('Honda-oops','Civic','2001');
        $vehicle3 = $this->createMMY('Honda','Civic','2002');
        
        $slaveLevels = array(
            array('make', $vehicle1 ),
            array('make', $vehicle2 ),
        );
        $masterLevel = array('make', $vehicle1 );
        $this->levelFinder()->merge( $slaveLevels, $masterLevel );
        
        $this->assertTrue( $this->vehicleExists(array('make'=>'Honda','model'=>'Civic','year'=>2000)) );
        $this->assertTrue( $this->vehicleExists(array('make'=>'Honda','model'=>'Civic','year'=>2001)) );
        $this->assertTrue( $this->vehicleExists(array('make'=>'Honda','model'=>'Civic','year'=>2002)), 'should add siblings of slaves' );
    }
    
    function testShouldMergeMake()
    {
        $vehicle1 = $this->createMMY('Honda','Civic','2000');
        $vehicle2 = $this->createMMY('Honda','Civic','2002');
        $vehicle3 = $this->createMMY('Acura','Integra','2001');
        
        $slaveLevels = array(
            array('make', $vehicle1 ),
            array('make', $vehicle3 ),
        );
        $masterLevel = array('make', $vehicle1 );

        $this->levelFinder()->merge( $slaveLevels, $masterLevel );
        
        $this->assertTrue( $this->vehicleExists(array('make'=>'Honda','model'=>'Civic','year'=>2000)) );
        $this->assertFalse( $this->vehicleExists(array('make'=>'Honda','model'=>'Civic','year'=>2001)) );
        $this->assertTrue( $this->vehicleExists(array('make'=>'Honda','model'=>'Integra','year'=>2001)) );
        
    }
    
    function testShouldMergeMake2()
    {
        $vehicle1 = $this->createMMY('Honda','Civic','2000');
        $vehicle2 = $this->createMMY('Ford','F-150','2001');
        
        $slaveLevels = array(
            array('make', $vehicle1 ),
            array('make', $vehicle2 ),
        );
        $masterLevel = array('make', $vehicle1 );
        
        
                                                              // it deletes the year from Ford, but not ford itself
        $this->levelFinder()->merge( $slaveLevels, $masterLevel );
        
        $select = new Elite_Vaf_Select($this->getReadAdapter());
        
        $this->assertTrue( $this->vehicleExists(array('make'=>'Honda','model'=>'Civic','year'=>2000)) );
        $this->assertTrue( $this->vehicleExists(array('make'=>'Honda','model'=>'F-150','year'=>2001)) );
        
        $this->assertFalse( $this->vehicleExists(array('make'=>'Honda','model'=>'Civic','year'=>2001)) );
        $this->assertFalse( $this->vehicleExists(array('make'=>'Honda','model'=>'F-150','year'=>2000)) );
        $this->assertFalse( $this->vehicleExists(array('make'=>'Ford'), true ));
    }
        
    function testShouldMergeMake3()
    {
        $vehicle1 = $this->createMMY('Honda','Civic','2000');
        $vehicle2 = $this->createMMY('Ford','F-150','2001');
        
        $slaveLevels = array(
            array('make', $vehicle1 ),
            array('make', $vehicle2 ),
        );
        $masterLevel = array('make', $vehicle2 );

        $this->levelFinder()->merge( $slaveLevels, $masterLevel );
        
        $select = new Elite_Vaf_Select($this->getReadAdapter());
        
        $this->assertFalse( $this->vehicleExists(array('make'=>'Honda')) );
        $this->assertTrue( $this->vehicleExists(array('make'=>'Ford'), true ));
    }
    
    function testShouldMergeProductFitments()
    {
        $vehicle1 = $this->createMMY('Honda','Civic','2000');
        $vehicle2 = $this->createMMY('Honda-oops','Civic','2001');
        
        $actual = $this->insertFitmentMMY($vehicle2, 1);
        
        $slaveLevels = array(
            array('make', $vehicle1 ),
            array('make', $vehicle2 ),
        );
        $masterLevel = array('make', $vehicle1 );
        $this->levelFinder()->merge( $slaveLevels, $masterLevel );
        
        $product = $this->newProduct(1);
        $product->setCurrentlySelectedFit($vehicle1);
        $this->assertFalse( $product->fitsSelection() );
    }
    
    function testShouldMergeProductFitments_Years()
    {
        $vehicle1 = $this->createMMY('Ford','F-150','2000');
        $vehicle2 = $this->createMMY('Ford','F150','2001');        
        
        $this->insertFitmentMMY($vehicle1, 1);
        $this->insertFitmentMMY($vehicle2, 1);
        
        $slaveLevels = array(
            array('model', $vehicle1 ),
            array('model', $vehicle2 ),
        );
        $masterLevel = array('model', $vehicle1 );
        $this->levelFinder()->merge( $slaveLevels, $masterLevel );
        
        $product = $this->newProduct(1);
        $product->setCurrentlySelectedFit($this->vehicleFinder()->findOneByLevels(array('year'=>'2000')));
        $this->assertTrue( $product->fitsSelection() );
        
        $product->setCurrentlySelectedFit($this->vehicleFinder()->findOneByLevels(array('year'=>'2001')));
        $this->assertTrue( $product->fitsSelection() );
    }
    
    function testShouldNotCreatePartialFitments()
    {
        $vehicle1 = $this->createMMY('Honda','Civic','2000');
        $vehicle2 = $this->createMMY('Honda-oops','Civic','2001');
        
        $actual = $this->insertFitmentMMY($vehicle2, 1);
        
        $slaveLevels = array(
            array('make', $vehicle1 ),
            array('make', $vehicle2 ),
        );
        $masterLevel = array('make', $vehicle1 );
        $this->levelFinder()->merge( $slaveLevels, $masterLevel );
        
        $count = $this->getReadAdapter()->select()->from('elite_Fitment', array('count(*)'))->where('year_id = 0')->query()->fetchColumn();
        $this->assertEquals( 0, $count );
    }
    
    function testShouldClearVehicleFinderIdentityMap()
    {
        return $this->markTestIncomplete();
    }
    
    function testShouldClearLevelFinderIdentityMap()
    {
        return $this->markTestIncomplete();
    }
    
}