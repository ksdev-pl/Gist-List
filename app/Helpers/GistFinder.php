<?php

namespace App\Helpers;

class GistFinder
{
    /** @var \Github\Api\ApiInterface $gistsApi */
    private $gistsApi;

    /** @var \Github\ResultPager $paginator */
    private $paginator;

    /** @var \GuzzleHttp\Client $guzzle */
    private $guzzle;

    public function __construct(
        \Github\Client $githubClient,
        \Github\ResultPager $paginator,
        \GuzzleHttp\Client $guzzleClient
    ) {
        $githubClient->authenticate(\Auth::user()->token, null, \Github\Client::AUTH_HTTP_TOKEN);

        $this->gistsApi = $githubClient->api('gists');
        $this->paginator = $paginator;
        $this->guzzle = $guzzleClient;
    }

    /**
     * Get gists and tags.
     *
     * @return array `['gists' => [...], 'tags' => [...]]`
     */
    public function getGistsAndTags()
    {
        $rawMergedGists = $this->fetchRawGists();

        // prepare gists and tags arrays
        $gists = [];
        $tags = [];
        foreach ($rawMergedGists as $gist) {
            // separate description and tags
            $descAndTags = $this->separateDescriptionAndTags($gist['description']);

            // set gist owner
            $gistOwner = isset($gist['owner']) ? $gist['owner']['login'] : 'anonymous';

            // set gist type
            $gistType = [];
            if (isset($gist['starred'])) {
                $gistType[] = '@starred';
            }
            if (\Auth::user()->nickname == $gistOwner) {
                $gistType[] = '@owned';
            }
            if ($gist['public']) {
                $gistType[] = '@public';
            } else {
                $gistType[] = '@private';
            }

            // prepare files array
            $files = [];
            foreach ($gist['files'] as $file) {
                $files[] = [
                    'filename' => $file['filename'],
                    'raw_url'  => $file['raw_url']
                ];
            }

            // add gist to gists array
            $gists[] = [
                'id'          => $gist['id'],
                'description' => $descAndTags['description'] ? $descAndTags['description'] : 'No description',
                'tags'        => $descAndTags['tags'],
                'owner'       => $gistOwner,
                'created'     => date('Y-m-d H:i:s', strtotime($gist['created_at'])),
                'updated'     => date('Y-m-d H:i:s', strtotime($gist['updated_at'])),
                'type'        => $gistType,
                'html_url'    => $gist['html_url'],
                'files'       => $files
            ];

            // add tags to tags array
            foreach ($descAndTags['tags'] as $tag) {
                if (!in_array($tag, $tags)) {
                    $tags[] = $tag;
                }
            }
        }

        return compact('gists', 'tags');
    }

    /**
     * Fetch raw gists from GitHub.
     *
     * @return array
     */
    public function fetchRawGists()
    {
        // fetch user and starred gists
        $rawUserGists = collect($this->paginator->fetchAll($this->gistsApi, 'all'))
            ->keyBy('id')
            ->toArray();
        $rawStarredGists = collect($this->paginator->fetchAll($this->gistsApi, 'all', ['starred']))
            ->keyBy('id')
            ->toArray();

        // add starred label to starred gists
        foreach ($rawStarredGists as $key => $gist) {
            $rawStarredGists[$key]['starred'] = true;
        }

        // merge user and starred gists
        $rawMergedGists = $rawStarredGists + $rawUserGists;

        return $rawMergedGists;
    }

    /**
     * Get the content of a Gist file.
     *
     * @param string $url
     *
     * @return string
     */
    public function getGistFileContents($url)
    {
        return (string)$this->guzzle->get($url)->getBody();
    }

    /**
     * Separate gist description and tags.
     *
     * Seeks tags in original gist description and saves them and description separately.
     *
     * @param string $descriptionWithTags Gist description with optional tags.
     *
     * @return array `['description' => '...', 'tags' => [...]]`
     */
    private function separateDescriptionAndTags($descriptionWithTags)
    {
        $tags = [];
        $description = trim(
            preg_replace_callback(
                '~\s(#\S+)~',
                function ($matches) use (&$tags) {
                    $tags[] = $matches[1];
                },
                $descriptionWithTags
            )
        );

        if (empty($tags)) {
            $tags[] = '#none';
        }

        return compact('description', 'tags');
    }
}
