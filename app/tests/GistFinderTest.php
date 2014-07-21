<?php

class GistFinderTest extends TestCase
{
    /** @type \Mockery\MockInterface */
    public $githubApiMock;

    /** @type GistFinder */
    public $gistFinder;

    public function setUp()
    {
        parent::setUp();

        $this->githubApiMock = Mockery::mock('AbstractGithubApi');
        $this->gistFinder = new GistFinder($this->githubApiMock, new GistFactory());
    }

    public function testGetAllWhenUserAndStarredGistsAreAvailable()
    {
        $this->githubApiMock
            ->shouldReceive('getGistsOfAuthUser')
            ->once()
            ->with()
            ->andReturn(
                [
                    0 => [
                        'id'          => 0,
                        'description' => 'description',
                        'created_at'  => '2010-04-14T02:15:15Z',
                        'updated_at'  => '2011-06-20T11:34:15Z',
                        'public'      => false,
                        'html_url'    => 'https://github.com/octocat',
                        'files'       => [
                            'test.md' => [
                                'filename' => 'test.md'
                            ]
                        ],
                        'owner'       => [
                            'id' => 0
                        ]
                    ],
                    1 => [
                        'id'          => 1,
                        'description' => 'description #tag1 #tag2',
                        'created_at'  => '2010-04-14T02:15:15Z',
                        'updated_at'  => '2011-06-20T11:34:15Z',
                        'public'      => false,
                        'html_url'    => 'https://github.com/octocat',
                        'files'       => [
                            'test.md' => [
                                'filename' => 'test.md'
                            ]
                        ],
                        'owner'       => [
                            'id' => 0
                        ]
                    ]
                ]
            );

        $this->githubApiMock
            ->shouldReceive('getGistsOfAuthUser')
            ->once()
            ->with(true)
            ->andReturn(
                [
                    0 => [
                        'id'          => 10,
                        'description' => 'description',
                        'created_at'  => '2010-04-14T02:15:15Z',
                        'updated_at'  => '2011-06-20T11:34:15Z',
                        'public'      => true,
                        'html_url'    => 'https://github.com/octocat',
                        'files'       => [
                            'test.md' => [
                                'filename' => 'test.md'
                            ]
                        ],
                        'owner'       => [
                            'id' => 1
                        ]
                    ],
                    1 => [
                        'id'          => 0,
                        'description' => 'description',
                        'created_at'  => '2010-04-14T02:15:15Z',
                        'updated_at'  => '2011-06-20T11:34:15Z',
                        'public'      => false,
                        'html_url'    => 'https://github.com/octocat',
                        'files'       => [
                            'test.md' => [
                                'filename' => 'test.md'
                            ]
                        ],
                        'owner'       => [
                            'id' => 0
                        ]
                    ]
                ]
            );

        $gists = $this->gistFinder->getAll();

        $this->assertEquals(1, $gists[0]->getId());
        $this->assertEquals('description', $gists[0]->getDescription());
        $this->assertEquals(['#tag1', '#tag2'], $gists[0]->getTags());
        $this->assertEquals('2010-04-14 02:15:15', $gists[0]->getCreatedAt());
        $this->assertEquals('2011-06-20 11:34:15', $gists[0]->getUpdatedAt());
        $this->assertEquals(false, $gists[0]->isPublic());
        $this->assertEquals('https://github.com/octocat', $gists[0]->getHtmlUrl());
        $this->assertEquals('test.md', $gists[0]->getFiles()['test.md']['filename']);
        $this->assertEquals(false, $gists[0]->isStarred());

        $this->assertEquals(0, $gists[2]->getId());
        $this->assertEquals(true, $gists[2]->isStarred());

        $this->assertFalse(isset($gists[3]));
    }

    public function testGetAllWhenUserGistsArrayIsEmpty()
    {
        $this->githubApiMock
            ->shouldReceive('getGistsOfAuthUser')
            ->once()
            ->with()
            ->andReturn([]);

        $this->githubApiMock
            ->shouldReceive('getGistsOfAuthUser')
            ->once()
            ->with(true)
            ->andReturn(
                [
                    0 => [
                        'id'          => 0,
                        'description' => 'test',
                        'created_at'  => '2010-04-14T02:15:15Z',
                        'updated_at'  => '2011-06-20T11:34:15Z',
                        'public'      => true,
                        'html_url'    => 'https://github.com/octocat',
                        'files'       => [
                            'test.md' => [
                                'filename' => 'test.md'
                            ]
                        ],
                        'owner'       => [
                            'id' => 0
                        ]
                    ],
                    1 => [
                        'id'          => 1,
                        'description' => 'test',
                        'created_at'  => '2010-04-14T02:15:15Z',
                        'updated_at'  => '2011-06-20T11:34:15Z',
                        'public'      => true,
                        'html_url'    => 'https://github.com/octocat',
                        'files'       => [
                            'test.md' => [
                                'filename' => 'test.md'
                            ]
                        ],
                        'owner'       => [
                            'id' => 1
                        ]
                    ]
                ]
            );

        $gists = $this->gistFinder->getAll();

        $this->assertEquals(0, $gists[0]->getId());
        $this->assertEquals(true, $gists[0]->isStarred());

        $this->assertEquals(1, $gists[1]->getId());
        $this->assertEquals(true, $gists[1]->isStarred());
    }

    public function testGetAllWhenStarredGistsArrayIsEmpty()
    {
        $this->githubApiMock
            ->shouldReceive('getGistsOfAuthUser')
            ->once()
            ->with()
            ->andReturn(
                [
                    0 => [
                        'id'          => 0,
                        'description' => 'test',
                        'created_at'  => '2010-04-14T02:15:15Z',
                        'updated_at'  => '2011-06-20T11:34:15Z',
                        'public'      => false,
                        'html_url'    => 'https://github.com/octocat',
                        'files'       => [
                            'test.md' => [
                                'filename' => 'test.md'
                            ]
                        ],
                        'owner'       => [
                            'id' => 0
                        ]
                    ],
                    1 => [
                        'id'          => 1,
                        'description' => 'test',
                        'created_at'  => '2010-04-14T02:15:15Z',
                        'updated_at'  => '2011-06-20T11:34:15Z',
                        'public'      => false,
                        'html_url'    => 'https://github.com/octocat',
                        'files'       => [
                            'test.md' => [
                                'filename' => 'test.md'
                            ]
                        ],
                        'owner'       => [
                            'id' => 0
                        ]
                    ]
                ]
            );

        $this->githubApiMock
            ->shouldReceive('getGistsOfAuthUser')
            ->once()
            ->with(true)
            ->andReturn([]);

        $gists = $this->gistFinder->getAll();

        $this->assertEquals(0, $gists[0]->getId());
        $this->assertEquals(false, $gists[0]->isStarred());

        $this->assertEquals(1, $gists[1]->getId());
        $this->assertEquals(false, $gists[1]->isStarred());
    }

    public function testGetAllWhenThereAreNoGists()
    {
        $this->githubApiMock
            ->shouldReceive('getGistsOfAuthUser')
            ->once()
            ->with()
            ->andReturn([]);

        $this->githubApiMock
            ->shouldReceive('getGistsOfAuthUser')
            ->once()
            ->with(true)
            ->andReturn([]);

        $gists = $this->gistFinder->getAll();

        $this->assertEquals([], $gists);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}