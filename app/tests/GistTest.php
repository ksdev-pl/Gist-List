<?php

class GistTest extends TestCase
{
    public function testGetListOfCountedTags()
    {
        $gist1 = new Gist();
        $gist1->setDescriptionAndTags('description #tag1 #tag2');
        $gist1->setPublic(false);
        $gist1->setOwner(['id' => 0]);
        $gist1->setStarred(false);

        $gist2 = new Gist();
        $gist2->setDescriptionAndTags('description #tag1 #tag2 #tag3');
        $gist2->setPublic(false);
        $gist2->setOwner(['id' => 0]);
        $gist2->setStarred(false);

        $gist3 = new Gist();
        $gist3->setDescriptionAndTags('description #tag3');
        $gist3->setPublic(false);
        $gist3->setOwner(['id' => 0]);
        $gist3->setStarred(true);

        $gist4 = new Gist();
        $gist4->setDescriptionAndTags('description');
        $gist4->setPublic(true);
        $gist4->setOwner(['id' => 1]);
        $gist4->setStarred(true);

        $gists = [$gist1, $gist2, $gist3, $gist4];

        $tags = Gist::getListOfCountedTags($gists, $ownerId = 0);

        $expected = [
            'public'  => 1,
            'private' => 3,
            'noTag'   => 1,
            'all'     => 4,
            'myGists' => 3,
            'starred' => 2,
            'tags'    => [
                0 => [
                    'name'  => '#tag1',
                    'count' => 2
                ],
                1 => [
                    'name'  => '#tag2',
                    'count' => 2
                ],
                2 => [
                    'name'  => '#tag3',
                    'count' => 2
                ]
            ]
        ];

        $this->assertEquals($expected, $tags);
    }
}