  <?php
  /**
   * All WebservicePlugin plugin tests
   */
  class AllWebservicePluginTest extends CakeTestCase {

  /**
   * Suite define the tests for this suite
   *
   * @return void
   */
    public static function suite() {
      $suite = new CakeTestSuite('All WebservicePlugin test');

      $path = CakePlugin::path('WebservicePlugin') . 'Test' . DS . 'Case' . DS;
      $suite->addTestDirectoryRecursive($path);

      return $suite;
    }

  }
