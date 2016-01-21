<?php
namespace Composer\Installers\Test;

use Composer\Composer;
use Composer\Config;
use Composer\Installers\Installer;
use Composer\Package\Package;
use Composer\Package\RootPackage;
use Composer\Util\Filesystem;

class InstallerTest extends TestCase {
	private $composer;
	private $config;
	private $vendorDir;
	private $binDir;
	private $dm;
	private $repository;
	private $io;
	private $fs;

	/**
	 * setUp
	 *
	 * @return void
	 */
	public function setUp() {
		$this->fs = new Filesystem;

		$this->composer = new Composer();
		$this->config = new Config();
		$this->composer->setConfig($this->config);

		$this->vendorDir = realpath(sys_get_temp_dir()) . DIRECTORY_SEPARATOR . 'baton-test-vendor';
		$this->ensureDirectoryExistsAndClear($this->vendorDir);

		$this->binDir = realpath(sys_get_temp_dir()) . DIRECTORY_SEPARATOR . 'baton-test-bin';
		$this->ensureDirectoryExistsAndClear($this->binDir);

		$this->config->merge(array(
				'config' => array(
						'vendor-dir' => $this->vendorDir,
						'bin-dir' => $this->binDir,
				),
		));

		$this->dm = $this->getMockBuilder('Composer\Downloader\DownloadManager')
				->disableOriginalConstructor()
				->getMock();
		$this->composer->setDownloadManager($this->dm);

		$this->repository = $this->getMock('Composer\Repository\InstalledRepositoryInterface');
		$this->io = $this->getMock('Composer\IO\IOInterface');
	}

	/**
	 * tearDown
	 *
	 * @return void
	 */
	public function tearDown() {
		$this->fs->removeDirectory($this->vendorDir);
		$this->fs->removeDirectory($this->binDir);
	}

	/**
	 * testSupports
	 *
	 * @return void
	 *
	 * @dataProvider dataForTestSupport
	 */
	public function testSupports($type, $expected) {
		$installer = new Installer($this->io, $this->composer);
		$this->assertSame($expected, $installer->supports($type), sprintf('Failed to show support for %s', $type));
	}

	/**
	 * dataForTestSupport
	 */
	public function dataForTestSupport() {
		return array(
				array('agl-module', TRUE),
				array('aimeos-extension', TRUE),
				array('annotatecms-module', TRUE),
				array('annotatecms-component', TRUE),
				array('annotatecms-service', TRUE),
				array('bitrix-module', TRUE),
				array('bitrix-component', TRUE),
				array('bitrix-theme', TRUE),
				array('bonefish-package', TRUE),
				array('cakephp', FALSE),
				array('cakephp-', FALSE),
				array('cakephp-app', FALSE),
				array('cakephp-plugin', TRUE),
				array('chef-cookbook', TRUE),
				array('chef-role', TRUE),
				array('codeigniter-app', FALSE),
				array('codeigniter-library', TRUE),
				array('codeigniter-third-party', TRUE),
				array('codeigniter-module', TRUE),
				array('concrete5-block', TRUE),
				array('concrete5-package', TRUE),
				array('concrete5-theme', TRUE),
				array('concrete5-update', TRUE),
				array('craft-plugin', TRUE),
				array('croogo-plugin', TRUE),
				array('croogo-theme', TRUE),
				array('dokuwiki-plugin', TRUE),
				array('dokuwiki-template', TRUE),
				array('drupal-module', TRUE),
				array('dolibarr-module', TRUE),
				array('elgg-plugin', TRUE),
				array('fuel-module', TRUE),
				array('fuel-package', TRUE),
				array('fuel-theme', TRUE),
				array('fuelphp-component', TRUE),
				array('hurad-plugin', TRUE),
				array('hurad-theme', TRUE),
				array('joomla-library', TRUE),
				array('kirby-plugin', TRUE),
				array('kohana-module', TRUE),
				array('laravel-library', TRUE),
				array('lithium-library', TRUE),
				array('magento-library', TRUE),
				array('mako-package', TRUE),
				array('modxevo-snippet', TRUE),
				array('modxevo-plugin', TRUE),
				array('modxevo-module', TRUE),
				array('modxevo-template', TRUE),
				array('modxevo-lib', TRUE),
				array('mediawiki-extension', TRUE),
				array('mediawiki-skin', TRUE),
				array('microweber-module', TRUE),
				array('modulework-module', TRUE),
				array('moodle-mod', TRUE),
				array('october-module', TRUE),
				array('october-plugin', TRUE),
				array('piwik-plugin', TRUE),
				array('phpbb-extension', TRUE),
				array('pimcore-plugin', TRUE),
				array('ppi-module', TRUE),
				array('prestashop-module', TRUE),
				array('prestashop-theme', TRUE),
				array('puppet-module', TRUE),
				array('redaxo-addon', TRUE),
				array('redaxo-bestyle-plugin', TRUE),
				array('roundcube-plugin', TRUE),
				array('shopware-backend-plugin', TRUE),
				array('shopware-core-plugin', TRUE),
				array('shopware-frontend-plugin', TRUE),
				array('shopware-theme', TRUE),
				array('silverstripe-module', TRUE),
				array('silverstripe-theme', TRUE),
				array('smf-module', TRUE),
				array('smf-theme', TRUE),
				array('symfony1-plugin', TRUE),
				array('thelia-module', TRUE),
				array('thelia-frontoffice-template', TRUE),
				array('thelia-backoffice-template', TRUE),
				array('thelia-email-template', TRUE),
				array('tusk-task', TRUE),
				array('tusk-asset', TRUE),
				array('typo3-flow-plugin', TRUE),
				array('typo3-cms-extension', TRUE),
				array('whmcs-gateway', TRUE),
				array('wolfcms-plugin', TRUE),
				array('wordpress-plugin', TRUE),
				array('wordpress-core', FALSE),
				array('zend-library', TRUE),
				array('zikula-module', TRUE),
				array('zikula-theme', TRUE),
				array('kodicms-plugin', TRUE),
				array('kodicms-media', TRUE),
		);
	}

	/**
	 * testInstallPath
	 *
	 * @dataProvider dataForTestInstallPath
	 */
	public function testInstallPath($type, $path, $name, $version = '1.0.0') {
		$installer = new Installer($this->io, $this->composer);
		$package = new Package($name, $version, $version);

		$package->setType($type);
		$result = $installer->getInstallPath($package);
		$this->assertEquals($path, $result);
	}

	/**
	 * dataFormTestInstallPath
	 */
	public function dataForTestInstallPath() {
		return array(
				array('agl-module', 'More/MyTestPackage/', 'agl/my_test-package'),
				array('aimeos-extension', 'ext/ai-test/', 'author/ai-test'),
				array('annotatecms-module', 'addons/modules/my_module/', 'vysinsky/my_module'),
				array('annotatecms-component', 'addons/components/my_component/', 'vysinsky/my_component'),
				array('annotatecms-service', 'addons/services/my_service/', 'vysinsky/my_service'),
				array('bitrix-module', 'bitrix/modules/my_module/', 'author/my_module'),
				array('bitrix-component', 'bitrix/components/my_component/', 'author/my_component'),
				array('bitrix-theme', 'bitrix/templates/my_theme/', 'author/my_theme'),
				array('bonefish-package', 'Packages/bonefish/package/', 'bonefish/package'),
				array('cakephp-plugin', 'Plugin/Ftp/', 'shama/ftp'),
				array('chef-cookbook', 'Chef/mre/my_cookbook/', 'mre/my_cookbook'),
				array('chef-role', 'Chef/roles/my_role/', 'mre/my_role'),
				array('codeigniter-library', 'application/libraries/my_package/', 'shama/my_package'),
				array('codeigniter-module', 'application/modules/my_package/', 'shama/my_package'),
				array('concrete5-block', 'blocks/concrete5_block/', 'remo/concrete5_block'),
				array('concrete5-package', 'packages/concrete5_package/', 'remo/concrete5_package'),
				array('concrete5-theme', 'themes/concrete5_theme/', 'remo/concrete5_theme'),
				array('concrete5-update', 'updates/concrete5/', 'concrete5/concrete5'),
				array('craft-plugin', 'craft/plugins/my_plugin/', 'mdcpepper/my_plugin'),
				array('croogo-plugin', 'Plugin/Sitemaps/', 'fahad19/sitemaps'),
				array('croogo-theme', 'View/Themed/Readable/', 'rchavik/readable'),
				array('dokuwiki-plugin', 'lib/plugins/someplugin/', 'author/someplugin'),
				array('dokuwiki-template', 'lib/tpl/sometemplate/', 'author/sometemplate'),
				array('dolibarr-module', 'htdocs/custom/my_module/', 'shama/my_module'),
				array('drupal-module', 'modules/my_module/', 'shama/my_module'),
				array('drupal-theme', 'themes/my_module/', 'shama/my_module'),
				array('drupal-profile', 'profiles/my_module/', 'shama/my_module'),
				array('drupal-drush', 'drush/my_module/', 'shama/my_module'),
				array('elgg-plugin', 'mod/sample_plugin/', 'test/sample_plugin'),
				array('fuel-module', 'fuel/app/modules/module/', 'fuel/module'),
				array('fuel-package', 'fuel/packages/orm/', 'fuel/orm'),
				array('fuel-theme', 'fuel/app/themes/theme/', 'fuel/theme'),
				array('fuelphp-component', 'components/demo/', 'fuelphp/demo'),
				array('hurad-plugin', 'plugins/Akismet/', 'atkrad/akismet'),
				array('hurad-theme', 'plugins/Hurad2013/', 'atkrad/Hurad2013'),
				array('joomla-plugin', 'plugins/my_plugin/', 'shama/my_plugin'),
				array('kirby-plugin', 'site/plugins/my_plugin/', 'shama/my_plugin'),
				array('kohana-module', 'modules/my_package/', 'shama/my_package'),
				array('laravel-library', 'libraries/my_package/', 'shama/my_package'),
				array('lithium-library', 'libraries/li3_test/', 'user/li3_test'),
				array('magento-library', 'lib/foo/', 'test/foo'),
				array('modxevo-snippet', 'assets/snippets/my_snippet/', 'shama/my_snippet'),
				array('modxevo-plugin', 'assets/plugins/my_plugin/', 'shama/my_plugin'),
				array('modxevo-module', 'assets/modules/my_module/', 'shama/my_module'),
				array('modxevo-template', 'assets/templates/my_template/', 'shama/my_template'),
				array('modxevo-lib', 'assets/lib/my_lib/', 'shama/my_lib'),
				array('mako-package', 'app/packages/my_package/', 'shama/my_package'),
				array('mediawiki-extension', 'extensions/APC/', 'author/APC'),
				array('mediawiki-extension', 'extensions/APC/', 'author/APC-extension'),
				array('mediawiki-extension', 'extensions/UploadWizard/', 'author/upload-wizard'),
				array('mediawiki-extension', 'extensions/SyntaxHighlight_GeSHi/', 'author/syntax-highlight_GeSHi'),
				array('mediawiki-skin', 'skins/someskin/', 'author/someskin-skin'),
				array('mediawiki-skin', 'skins/someskin/', 'author/someskin'),
				array('microweber-module', 'userfiles/modules/my-thing/', 'author/my-thing-module'),
				array('modulework-module', 'modules/my_package/', 'shama/my_package'),
				array('moodle-mod', 'mod/my_package/', 'shama/my_package'),
				array('october-module', 'modules/my_plugin/', 'shama/my_plugin'),
				array('october-plugin', 'plugins/shama/my_plugin/', 'shama/my_plugin'),
				array('october-theme', 'themes/my_theme/', 'shama/my_theme'),
				array('piwik-plugin', 'plugins/VisitSummary/', 'shama/visit-summary'),
				array('prestashop-module', 'modules/a-module/', 'vendor/a-module'),
				array('prestashop-theme', 'themes/a-theme/', 'vendor/a-theme'),
				array('phpbb-extension', 'ext/test/foo/', 'test/foo'),
				array('phpbb-style', 'styles/foo/', 'test/foo'),
				array('phpbb-language', 'language/foo/', 'test/foo'),
				array('pimcore-plugin', 'plugins/MyPlugin/', 'ubikz/my_plugin'),
				array('ppi-module', 'modules/foo/', 'test/foo'),
				array('puppet-module', 'modules/puppet-name/', 'puppet/puppet-name'),
				array('redaxo-addon', 'redaxo/include/addons/my_plugin/', 'shama/my_plugin'),
				array('redaxo-bestyle-plugin', 'redaxo/include/addons/be_style/plugins/my_plugin/', 'shama/my_plugin'),
				array('roundcube-plugin', 'plugins/base/', 'test/base'),
				array('roundcube-plugin', 'plugins/replace_dash/', 'test/replace-dash'),
				array('shopware-backend-plugin', 'engine/Shopware/Plugins/Local/Backend/ShamaMyBackendPlugin/', 'shama/my-backend-plugin'),
				array('shopware-core-plugin', 'engine/Shopware/Plugins/Local/Core/ShamaMyCorePlugin/', 'shama/my-core-plugin'),
				array('shopware-frontend-plugin', 'engine/Shopware/Plugins/Local/Frontend/ShamaMyFrontendPlugin/', 'shama/my-frontend-plugin'),
				array('shopware-theme', 'templates/my_theme/', 'shama/my-theme'),
				array('silverstripe-module', 'my_module/', 'shama/my_module'),
				array('silverstripe-module', 'sapphire/', 'silverstripe/framework', '2.4.0'),
				array('silverstripe-module', 'framework/', 'silverstripe/framework', '3.0.0'),
				array('silverstripe-module', 'framework/', 'silverstripe/framework', '3.0.0-rc1'),
				array('silverstripe-module', 'framework/', 'silverstripe/framework', 'my/branch'),
				array('silverstripe-theme', 'themes/my_theme/', 'shama/my_theme'),
				array('smf-module', 'Sources/my_module/', 'shama/my_module'),
				array('smf-theme', 'Themes/my_theme/', 'shama/my_theme'),
				array('symfony1-plugin', 'plugins/sfShamaPlugin/', 'shama/sfShamaPlugin'),
				array('symfony1-plugin', 'plugins/sfShamaPlugin/', 'shama/sf-shama-plugin'),
				array('thelia-module', 'local/modules/my_module/', 'shama/my_module'),
				array('thelia-frontoffice-template', 'templates/frontOffice/my_template_fo/', 'shama/my_template_fo'),
				array('thelia-backoffice-template', 'templates/backOffice/my_template_bo/', 'shama/my_template_bo'),
				array('thelia-email-template', 'templates/email/my_template_email/', 'shama/my_template_email'),
				array('tusk-task', '.tusk/tasks/my_task/', 'shama/my_task'),
				array('typo3-flow-package', 'Packages/Application/my_package/', 'shama/my_package'),
				array('typo3-flow-build', 'Build/my_package/', 'shama/my_package'),
				array('typo3-cms-extension', 'typo3conf/ext/my_extension/', 'shama/my_extension'),
				array('whmcs-gateway', 'modules/gateways/gateway_name/', 'vendor/gateway_name'),
				array('wolfcms-plugin', 'wolf/plugins/my_plugin/', 'shama/my_plugin'),
				array('wordpress-plugin', 'wp-content/plugins/my_plugin/', 'shama/my_plugin'),
				array('wordpress-muplugin', 'wp-content/mu-plugins/my_plugin/', 'shama/my_plugin'),
				array('zend-extra', 'extras/library/zend_test/', 'shama/zend_test'),
				array('zikula-module', 'modules/my-test_module/', 'my/test_module'),
				array('zikula-theme', 'themes/my-test_theme/', 'my/test_theme'),
				array('kodicms-media', 'cms/media/vendor/my_media/', 'shama/my_media'),
				array('kodicms-plugin', 'cms/plugins/my_plugin/', 'shama/my_plugin'),
		);
	}

	/**
	 * testGetCakePHPInstallPathException
	 *
	 * @return void
	 *
	 * @expectedException \InvalidArgumentException
	 */
	public function testGetCakePHPInstallPathException() {
		$installer = new Installer($this->io, $this->composer);
		$package = new Package('shama/ftp', '1.0.0', '1.0.0');

		$package->setType('cakephp-whoops');
		$result = $installer->getInstallPath($package);
	}

	/**
	 * testCustomInstallPath
	 */
	public function testCustomInstallPath() {
		$installer = new Installer($this->io, $this->composer);
		$package = new Package('shama/ftp', '1.0.0', '1.0.0');
		$package->setType('cakephp-plugin');
		$consumerPackage = new RootPackage('foo/bar', '1.0.0', '1.0.0');
		$this->composer->setPackage($consumerPackage);
		$consumerPackage->setExtra(array(
				'installer-paths' => array(
						'my/custom/path/{$name}/' => array(
								'shama/ftp',
								'foo/bar',
						),
				),
		));
		$result = $installer->getInstallPath($package);
		$this->assertEquals('my/custom/path/Ftp/', $result);
	}

	/**
	 * testCustomInstallerName
	 */
	public function testCustomInstallerName() {
		$installer = new Installer($this->io, $this->composer);
		$package = new Package('shama/cakephp-ftp-plugin', '1.0.0', '1.0.0');
		$package->setType('cakephp-plugin');
		$package->setExtra(array(
				'installer-name' => 'FTP',
		));
		$result = $installer->getInstallPath($package);
		$this->assertEquals('Plugin/FTP/', $result);
	}

	/**
	 * testCustomTypePath
	 */
	public function testCustomTypePath() {
		$installer = new Installer($this->io, $this->composer);
		$package = new Package('slbmeh/my_plugin', '1.0.0', '1.0.0');
		$package->setType('wordpress-plugin');
		$consumerPackage = new RootPackage('foo/bar', '1.0.0', '1.0.0');
		$this->composer->setPackage($consumerPackage);
		$consumerPackage->setExtra(array(
				'installer-paths' => array(
						'my/custom/path/{$name}/' => array(
								'type:wordpress-plugin'
						),
				),
		));
		$result = $installer->getInstallPath($package);
		$this->assertEquals('my/custom/path/my_plugin/', $result);
	}

	/**
	 * testNoVendorName
	 */
	public function testNoVendorName() {
		$installer = new Installer($this->io, $this->composer);
		$package = new Package('sfPhpunitPlugin', '1.0.0', '1.0.0');

		$package->setType('symfony1-plugin');
		$result = $installer->getInstallPath($package);
		$this->assertEquals('plugins/sfPhpunitPlugin/', $result);
	}

	/**
	 * testTypo3Inflection
	 */
	public function testTypo3Inflection() {
		$installer = new Installer($this->io, $this->composer);
		$package = new Package('typo3/fluid', '1.0.0', '1.0.0');

		$package->setAutoload(array(
				'psr-0' => array(
						'TYPO3\\Fluid' => 'Classes',
				),
		));

		$package->setType('typo3-flow-package');
		$result = $installer->getInstallPath($package);
		$this->assertEquals('Packages/Application/TYPO3.Fluid/', $result);
	}

	public function testUninstallAndDeletePackageFromLocalRepo() {
		$package = new Package('foo', '1.0.0', '1.0.0');

		$installer = $this->getMock('Composer\Installers\Installer', array('getInstallPath'), array($this->io, $this->composer));
		$installer->expects($this->once())->method('getInstallPath')->with($package)->will($this->returnValue(sys_get_temp_dir() . '/foo'));

		$repo = $this->getMock('Composer\Repository\InstalledRepositoryInterface');
		$repo->expects($this->once())->method('hasPackage')->with($package)->will($this->returnValue(TRUE));
		$repo->expects($this->once())->method('removePackage')->with($package);

		$installer->uninstall($repo, $package);
	}
}
