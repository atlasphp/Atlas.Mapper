<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Thread;

/**
 * @property mixed $thread_id INTEGER
 * @property mixed $author_id INTEGER NOT NULL
 * @property mixed $subject VARCHAR(255) NOT NULL
 * @property mixed $body TEXT NOT NULL
 * @property null|false|\Atlas\Mapper\DataSource\Author\AuthorRecord $author
 * @property null|false|\Atlas\Mapper\DataSource\Summary\SummaryRecord $summary
 * @property null|\Atlas\Mapper\DataSource\Reply\ReplyRecordSet $replies
 * @property null|\Atlas\Mapper\DataSource\Tagging\TaggingRecordSet $taggings
 * @property null|false|\Atlas\Mapper\DataSource\Tag\TagRecord $tags
 */
trait ThreadFields
{
}
