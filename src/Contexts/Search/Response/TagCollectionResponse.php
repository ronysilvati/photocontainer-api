<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Response;

class TagCollectionResponse implements \JsonSerializable
{
    private $collection;

    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    public function jsonSerialize()
    {
        $out = [];
        foreach ($this->collection as $item) {
            $list = [
                'id' => $item['tag_group']['id'],
                'description' => $item['tag_group']['description'],
                'tags' => [],
            ];

            foreach ($item['tag_group']['list'] as $tag) {
                $list['tags'][] = ['id' => $tag->getId(), 'description' => $tag->getDescription()];
            }

            $out[] = $list;
        }

        return $out;
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 200;
    }
}
