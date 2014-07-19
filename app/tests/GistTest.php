<?php

class GistTest extends TestCase
{
    public function testGetListOfCountedTags()
    {
        $gist1 = new Gist();
        $gist1->setDescriptionAndTags('description #tag1 #tag2');
        $gist1->setIsPublic(false);
        $gist1->setOwner(['id' => 0]);
        $gist1->setIsStarred(false);

        $gist2 = new Gist();
        $gist2->setDescriptionAndTags('description #tag1 #tag2 #tag3');
        $gist2->setIsPublic(false);
        $gist2->setOwner(['id' => 0]);
        $gist2->setIsStarred(false);

        $gist3 = new Gist();
        $gist3->setDescriptionAndTags('description #tag3');
        $gist3->setIsPublic(false);
        $gist3->setOwner(['id' => 0]);
        $gist3->setIsStarred(true);

        $gist4 = new Gist();
        $gist4->setDescriptionAndTags('description');
        $gist4->setIsPublic(true);
        $gist4->setOwner(['id' => 1]);
        $gist4->setIsStarred(true);

        $gists = [$gist1, $gist2, $gist3, $gist4];

        $tags = Gist::getListOfCountedTags($gists, $ownerId = 0);

        $expected = [
            'public'  => 1,
            'private' => 3,
            'noTag'   => 1,
            'all'     => 4,
            'myGists' => 3,
            'starred' => 2,
            'tags' => [
                0 => [
                    'name' => '#tag1',
                    'count' => 2
                ],
                1 => [
                    'name' => '#tag2',
                    'count' => 2
                ],
                2 => [
                    'name' => '#tag3',
                    'count' => 2
                ]
            ]
        ];

        $this->assertEquals($expected, $tags);
    }
}