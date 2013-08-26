<?php
namespace Cloudwords\Tests\Integration;

class LanguageTest extends \PHPUnit_Framework_TestCase
{
    protected $client;
    
    public function setUp()
    {
        $this->client = new \Cloudwords\Client(TESTS_BASE_API_URL, TESTS_API_VERSION, TESTS_AUTH_TOKEN);
    }
    
    public function tearDown()
    {
    }

	/**
     * Test Case for all source language 
     */
    public function testAllSourceLanguagesExist()
    {
        try {
            $sourceLanguages = $this->client->getSourceLanguages();
        } catch(\Cloudwords\Exception $e ) {
            echo $e;
        }
  
        $this->assertTrue(is_array($sourceLanguages));
        $this->assertAllLanguagesExist($sourceLanguages);
    }

   /**
     * Test Case for all target language 
     */
    public function testAllTargetLanguagesExist()
    {
	    try {
	        $targetLanguages = $this->client->getTargetLanguages();
	    } catch(\Cloudwords\Exception $e) {
	        echo $e;
	    }

	    $this->assertTrue(is_array($targetLanguages));
	    $this->assertAllLanguagesExist($targetLanguages);
    }
    
    /**
     * Assert all language 
     * 
     * @param   array   $languages
     */
    private function assertAllLanguagesExist($languages)
    {
        $this->assertNotNull($languages);
        $this->assertEquals(36, count($languages));
        for( $index = 0; $index < count($languages); $index++ ) {
            $language = $languages[$index];
            $languageDisplay = $language->getDisplay();
            $languageCode = $language->getLanguageCode();
            switch($index) {
                case 0:
                    $this->assertEquals("Arabic", $languageDisplay);
                    $this->assertEquals("ar", $languageCode);
                    break;
                case 1:
                    $this->assertEquals("Bulgarian", $languageDisplay);
                    $this->assertEquals("bg", $languageCode);
                    break;
                case 2:
                    $this->assertEquals("Czech", $languageDisplay);
                    $this->assertEquals("cs", $languageCode);
                    break;
                case 3:
                    $this->assertEquals("Danish", $languageDisplay);
                    $this->assertEquals("da", $languageCode);
                    break;
                case 4:
                    $this->assertEquals("German", $languageDisplay);
                    $this->assertEquals("de", $languageCode);
                    break;
                case 5:
                    $this->assertEquals("Greek", $languageDisplay);
                    $this->assertEquals("el", $languageCode);
                    break;
                case 6:
                    $this->assertEquals("English", $languageDisplay);
                    $this->assertEquals("en", $languageCode);
                    break;
                case 7:
                    $this->assertEquals("English (UK)", $languageDisplay);
                    $this->assertEquals("en-gb", $languageCode);
                    break;
                case 8:
                    $this->assertEquals("English (US)", $languageDisplay);
                    $this->assertEquals("en-us", $languageCode);
                    break;
                case 9:
                    $this->assertEquals("Spanish", $languageDisplay);
                    $this->assertEquals("es", $languageCode);
                    break;
                case 10:
                    $this->assertEquals("Spanish (Spain)", $languageDisplay);
                    $this->assertEquals("es-es", $languageCode);
                    break;
                case 11:
                    $this->assertEquals("Spanish (Mexico)", $languageDisplay);
                    $this->assertEquals("es-mx", $languageCode);
                    break;
                case 12:
                    $this->assertEquals("Estonian", $languageDisplay);
                    $this->assertEquals("et", $languageCode);
                    break;
                case 13:
                    $this->assertEquals("Finnish", $languageDisplay);
                    $this->assertEquals("fi", $languageCode);
                    break;
                case 14:
                    $this->assertEquals("French", $languageDisplay);
                    $this->assertEquals("fr", $languageCode);
                    break;
                case 15:
                    $this->assertEquals("French (Canada)", $languageDisplay);
                    $this->assertEquals("fr-ca", $languageCode);
                    break;
                case 16:
                    $this->assertEquals("French (France)", $languageDisplay);
                    $this->assertEquals("fr-fr", $languageCode);
                    break;
                case 17:
                    $this->assertEquals("Hungarian", $languageDisplay);
                    $this->assertEquals("hu", $languageCode);
                    break;
                case 18:
                    $this->assertEquals("Italian", $languageDisplay);
                    $this->assertEquals("it", $languageCode);
                    break;
                case 19:
                    $this->assertEquals("Japanese", $languageDisplay);
                    $this->assertEquals("ja", $languageCode);
                    break;
                case 20:
                    $this->assertEquals("Korean", $languageDisplay);
                    $this->assertEquals("ko", $languageCode);
                    break;
                case 21:
                    $this->assertEquals("Lithuanian", $languageDisplay);
                    $this->assertEquals("lt", $languageCode);
                    break;
                case 22:
                    $this->assertEquals("Latvian", $languageDisplay);
                    $this->assertEquals("lv", $languageCode);
                    break;
                case 23:
                    $this->assertEquals("Dutch", $languageDisplay);
                    $this->assertEquals("nl", $languageCode);
                    break;
                case 24:
                    $this->assertEquals("Norwegian", $languageDisplay);
                    $this->assertEquals("no", $languageCode);
                    break;
                case 25:
                    $this->assertEquals("Polish", $languageDisplay);
                    $this->assertEquals("pl", $languageCode);
                    break;
                case 26:
                    $this->assertEquals("Portuguese", $languageDisplay);
                    $this->assertEquals("pt", $languageCode);
                    break;
                case 27:
                    $this->assertEquals("Portuguese (Brazil)", $languageDisplay);
                    $this->assertEquals("pt-br", $languageCode);
                    break;
                case 28:
                    $this->assertEquals("Portuguese (Portugal)", $languageDisplay);
                    $this->assertEquals("pt-pt", $languageCode);
                    break;
                case 29:
                    $this->assertEquals("Romanian (Romania)", $languageDisplay);
                    $this->assertEquals("ro", $languageCode);
                    break;
                case 30:
                    $this->assertEquals("Russian (Russia)", $languageDisplay);
                    $this->assertEquals("ru", $languageCode);
                    break;
                case 31:
                    $this->assertEquals("Swedish", $languageDisplay);
                    $this->assertEquals("sv", $languageCode);
                    break;
                case 32:
                    $this->assertEquals("Thai", $languageDisplay);
                    $this->assertEquals("th", $languageCode);
                    break;
                case 33:
                    $this->assertEquals("Turkish", $languageDisplay);
                    $this->assertEquals("tr", $languageCode);
                    break;
                case 34:
                    $this->assertEquals("Chinese (Simplified)", $languageDisplay);
                    $this->assertEquals("zh-cn", $languageCode);
                    break;
                case 35:
                    $this->assertEquals("Chinese (Traditional)", $languageDisplay);
                    $this->assertEquals("zh-tw", $languageCode);
                    break;
            }
        }
    }
}
