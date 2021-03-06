<?php

namespace tests;

use igogo5yo\uploadfromurl\UploadFromUrl;
use tests\models\Model;
use yii\helpers\FileHelper;
use Yii;

class UploadFromUrlTest extends \PHPUnit_Framework_TestCase
{
    const FILE_URL = 'http://static.yiiframework.com/files/logo/yii.png';
    const UPLOAD_DIR = 'uploads';

    protected function setUp()
    {
        if (!file_exists(self::UPLOAD_DIR)) {
            FileHelper::createDirectory(self::UPLOAD_DIR, 0777);
        }
    }

    protected function tearDown()
    {
        FileHelper::removeDirectory(self::UPLOAD_DIR);
    }

    // tests
    public function testOne()
    {
        $path = self::UPLOAD_DIR . '/yii.png';
        $model = new Model;
        $model->image = self::FILE_URL;

        $file = UploadFromUrl::getInstance($model, 'image');
        $file->saveAs($path, true);

        $this->assertEquals($path, $model->image);
        $this->assertTrue(file_exists($path));
    }

    public function testTwo()
    {
        $path = self::UPLOAD_DIR . '/yii.png';

        $file = UploadFromUrl::initWithUrl(self::FILE_URL);

        $file->saveAs($path);

        $this->assertTrue(file_exists($path));
    }

    public function testThree()
    {
        $path = self::UPLOAD_DIR . '/yii.png';
        $model = new Model;

        $file = UploadFromUrl::initWithUrlAndModel(self::FILE_URL, $model, 'image');
        $file->saveAs($path, true);

        $this->assertEquals($path, $model->image);
        $this->assertTrue(file_exists($path));
    }

    public function testFour()
    {
        $path = self::UPLOAD_DIR . '/yii.png';
        $model = new Model;

        $file = UploadFromUrl::initWithUrlAndModel(self::FILE_URL, $model, 'image');
        $file->saveAs($path, true);

        $this->assertTrue(!empty($file->baseName));
        $this->assertTrue(!empty($file->name));
        $this->assertTrue(!empty($file->extension));
    }

    public function testOptions()
    {
        $file = UploadFromUrl::initWithUrl(self::FILE_URL);

        $this->assertEquals('yii', $file->baseName);
        $this->assertEquals('png', $file->extension);
        $this->assertEquals('yii.png', $file->name);
        // Inthis test, size is non deterministic (Yii could change it's logo any time).
        // Making sure it is > 0 is enough
        $this->assertGreaterThan(0, $file->size);
    }
}
