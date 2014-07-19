<?php

class GistBackupHandlerTest extends TestCase
{
    /** @type \Mockery\MockInterface */
    public $githubApiMock;

    public function setUp()
    {
        parent::setUp();

        $this->githubApiMock = Mockery::mock('AbstractGithubApi');
    }

    public function testBackup()
    {
        $this->githubApiMock
            ->shouldReceive('getGistFileContents')
            ->twice()
            ->andReturn('test');

        $user = new User();
        $user->setId(1);
        $user->setLogin('test');

        $userFolderPath = storage_path() . '/backups/' . $user->getId();

        $gist1 = new Gist();
        $gist1->setUpdatedAt('2014-06-12 12:30:15');
        $gist1->setFiles(
            [
                'test.md' => [
                    'filename' => 'test.md',
                    'size'     => 1000,
                    'raw_url'  => 'https://gist.githubusercontent.com/raw/365370/7f2ac0ff512853564e/ring.erl'
                ]
            ]
        );

        $gist2 = new Gist();
        $gist2->setUpdatedAt('2000-06-12 12:30:15');
        $gist2->setFiles(
            [
                'test.md' => [
                    'filename' => 'test.md',
                    'size'     => 1000,
                    'raw_url'  => 'https://gist.githubusercontent.com/raw/365370/7f2ac0ff512853564e/ring.erl'
                ]
            ]
        );

        $gists = [$gist1, $gist2];

        $gistBackupHandler = new GistBackupHandler($this->githubApiMock, $user, $gists);
        $zipPath = $gistBackupHandler->backup();

        $this->assertFileExists($zipPath);
        $this->assertFileNotExists($userFolderPath . '/files/20140612123015-test.md');
        $this->assertFileNotExists($userFolderPath . '/files/20000613123015-test.md');

        unlink($zipPath);
        rmdir($userFolderPath . '/files');
        rmdir($userFolderPath . '/zip');
        rmdir($userFolderPath);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}