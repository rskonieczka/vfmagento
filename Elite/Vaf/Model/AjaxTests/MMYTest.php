<?php
class Elite_Vaf_Model_AjaxTests_MMYTest extends Elite_Vaf_TestCase
{
    function doSetUp()
    {
        $this->switchSchema('make,model,year');
    }    

    function testShouldListMakes()
    {
        $vehicle = $this->createMMY('Honda', 'Civic', '2000');
        $this->insertFitmentMMY($vehicle);
        $_GET['requestLevel'] = 'make';
        $this->assertEquals( '<option value="' . $vehicle->getValue('make') . '">Honda</option>', $this->execute(), 'should list makes' );
    }
     
    function testShouldListModels()
    {
        $vehicle = $this->createMMY('Honda', 'Civic', '2000');
        $this->insertFitmentMMY($vehicle);
        $_GET['make'] = $vehicle->getLevel('make')->getId();
        $_GET['requestLevel'] = 'model';
        $this->assertEquals( '<option value="' . $vehicle->getValue('model') . '">Civic</option>', $this->execute(), 'should list models for a make' );
    }    
    
    function testShouldListYears()
    {
        $vehicle = $this->createMMY('Honda', 'Civic', '2000');
        $this->insertFitmentMMY($vehicle);
        $_GET['make'] = $vehicle->getLevel('make')->getId();
        $_GET['model'] = $vehicle->getLevel('model')->getId();
        $_GET['requestLevel'] = 'year';
        $this->assertEquals( '<option value="' . $vehicle->getValue('year') . '">2000</option>', $this->execute(), 'should list years for a model' );
    }    
    
    function testShouldListYearsInUse()
    {
        $this->createMMY('Honda', 'Civic', '2001');
        $vehicle = $this->createMMY('Honda', 'Civic', '2000');
        $this->insertFitmentMMY($vehicle);
        $_GET['make'] = $vehicle->getLevel('make')->getId();
        $_GET['model'] = $vehicle->getLevel('model')->getId();
        $_GET['requestLevel'] = 'year';
        $this->assertEquals( '<option value="' . $vehicle->getValue('year') . '">2000</option>', $this->execute(), 'should list years for a model' );
    }    
    
    function testShouldListDistinctModelsWhenMultipleYears()
    {
        $vehicle1 = $this->createMMY('Honda', 'Civic', '2000');
        $vehicle2 = $this->createMMY('Honda', 'Civic', '2001');
        $this->insertFitmentMMY($vehicle1);
        $this->insertFitmentMMY($vehicle2);
        
        $_GET['make'] = $vehicle1->getLevel('make')->getId();
        $_GET['requestLevel'] = 'model';
        $this->assertEquals( '<option value="' . $vehicle1->getValue('model') . '">Civic</option>', $this->execute(), 'should list models for a make' );
    }
    
    function testShouldSortMake()
    {
        return $this->markTestIncomplete();
    }

    function testShouldSortModels()
    {
        return $this->markTestIncomplete();
    }
    
    function testShouldListMultipleModels()
    {
        $vehicle1 = $this->createMMY('Honda', 'Accord', '2000');
        $vehicle2 = $this->createMMY('Honda', 'Civic', '2001');
        $this->insertFitmentMMY($vehicle1);
        $this->insertFitmentMMY($vehicle2);
        $_GET['make'] = $vehicle1->getLevel('make')->getId();
        $_GET['requestLevel'] = 'model';
        $this->assertEquals( '<option value="0">-please select-</option><option value="' . $vehicle1->getValue('model') . '">Accord</option><option value="' . $vehicle2->getValue('model') . '">Civic</option>', $this->execute(), 'should list models for a make' );
    }
    
    function testShouldListMultipleModels_WithDefaultOption()
    {
        $vehicle1 = $this->createMMY('Honda', 'Accord', '2000');
        $vehicle2 = $this->createMMY('Honda', 'Civic', '2001');
        $this->insertFitmentMMY($vehicle1);
        $this->insertFitmentMMY($vehicle2);
        $_GET['make'] = $vehicle1->getLevel('make')->getId();
        $_GET['requestLevel'] = 'model';
        $_GET['front'] = true;
        $this->assertEquals( '<option value="0">-please select-</option><option value="' . $vehicle1->getValue('model') . '">Accord</option><option value="' . $vehicle2->getValue('model') . '">Civic</option>', $this->execute(), 'should list models for a make' );
    }
    
    function testShouldListMultipleModels_WithCustomDefaultOption()
    {
        $vehicle1 = $this->createMMY('Honda', 'Accord', '2000');
        $vehicle2 = $this->createMMY('Honda', 'Civic', '2001');
        $this->insertFitmentMMY($vehicle1);
        $this->insertFitmentMMY($vehicle2);
        $_GET['make'] = $vehicle1->getLevel('make')->getId();
        $_GET['requestLevel'] = 'model';
        $_GET['front'] = true;
        
        $ajax = $this->getAjax();
        $config = new Zend_Config(array('search'=>array('defaultText' => '-All %s-')));
        $ajax->setConfig($config);
        
        ob_start();
        $ajax->execute($this->getSchema());
        $actual = ob_get_clean();
        $expected = '<option value="0">-All Model-</option><option value="' . $vehicle1->getValue('model') . '">Accord</option><option value="' . $vehicle2->getValue('model') . '">Civic</option>';
        
        $this->assertEquals( $expected, $actual, 'should list models for a make' );
    }
    
    function testShouldListMultipleYears()
    {
        $vehicle1 = $this->createMMY('Honda', 'Civic', '2000');
        $vehicle2 = $this->createMMY('Honda', 'Civic', '2001');
        $vehicle3 = $this->createMMY('Honda', 'Civic', '2002');
        $this->insertFitmentMMY($vehicle1);
        $this->insertFitmentMMY($vehicle2);
        $this->insertFitmentMMY($vehicle3);
        $_GET['make'] = $vehicle1->getLevel('make')->getId();
        $_GET['model'] = $vehicle1->getLevel('model')->getId();
        $_GET['requestLevel'] = 'year';
        $this->assertEquals( '<option value="0">-please select-</option><option value="' . $vehicle1->getValue('year') . '">2000</option><option value="' . $vehicle2->getValue('year') . '">2001</option><option value="' . $vehicle3->getValue('year') . '">2002</option>', $this->execute(), 'should list models for a make' );
    }
    
    function execute()
    {
        ob_start();
        $_GET['front']=1;
        $this->getAjax()->execute( $this->getSchema() );
        return ob_get_clean();
    }
    
    /** @return Elite_Vaf_Model_Ajax */
    function getAjax()
    {
        return new Elite_Vaf_Model_Ajax();
    }
    
    /** @return Elite_Vaf_Model_Schema */
    function getSchema()
    {
        return new Elite_Vaf_Model_Schema();
    }
}