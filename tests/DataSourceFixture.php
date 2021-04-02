<?php
namespace Atlas\Mapper;

use Atlas\Pdo\Connection;

class DataSourceFixture
{
    protected $connection;

    public function __construct(Connection $connection = null)
    {
        $this->connection = $connection;
    }

    public function exec()
    {
        if ($this->connection === null) {
            $this->connection = Connection::new('sqlite::memory:');
        }

        $this->employee();

        $this->authors();
        $this->tags();
        $this->threads();
        $this->summaries();
        $this->taggings();
        $this->replies();

        $this->pages();
        $this->posts();
        $this->videos();
        $this->comments();

        $this->bidifoos();
        $this->bidibars();

        $this->nopkeys();

        // return the connection used
        return $this->connection;
    }

    protected function pages()
    {
        $this->connection->query("CREATE TABLE pages (
            page_id INTEGER PRIMARY KEY AUTOINCREMENT,
            title   VARCHAR(255),
            body    TEXT
        )");

        $stm = "INSERT INTO pages (page_id, title, body) VALUES (?, ?, ?)";
        for ($page_id = 1; $page_id <= 10; $page_id ++) {
            $title = "Page title {$page_id}";
            $body = "Page body {$page_id}";
            $this->connection->perform($stm, [$page_id, $title, $body]);
        }
    }

    protected function posts()
    {
        $this->connection->query("CREATE TABLE posts (
            post_id INTEGER PRIMARY KEY AUTOINCREMENT,
            subj    VARCHAR(255),
            body    TEXT
        )");

        $stm = "INSERT INTO posts (post_id, subj, body) VALUES (?, ?, ?)";
        for ($post_id = 1; $post_id <= 10; $post_id ++) {
            $subj = "Post subj {$post_id}";
            $body = "Post body {$post_id}";
            $this->connection->perform($stm, [$post_id, $subj, $body]);
        }
    }

    protected function videos()
    {
        $this->connection->query("CREATE TABLE videos (
            video_id INTEGER PRIMARY KEY AUTOINCREMENT,
            title    VARCHAR(255),
            url      VARCHAR(255)
        )");

        $stm = "INSERT INTO videos (video_id, title, url) VALUES (?, ?, ?)";
        for ($video_id = 1; $video_id <= 10; $video_id ++) {
            $title = "Video title {$video_id}";
            $url = "http://videos.example.net/{$video_id}";
            $this->connection->perform($stm, [$video_id, $title, $url]);
        }
    }

    protected function comments()
    {
        $this->connection->query("CREATE TABLE comments (
            comment_id   INTEGER PRIMARY KEY AUTOINCREMENT,
            related_type VARCHAR(255),
            related_id   INTEGER,
            body         TEXT
        )");

        // three comments on each of 10 related pages/posts/videos
        $stm = "INSERT INTO comments (comment_id, related_type, related_id, body) VALUES (?, ?, ?, ?)";
        $comment_id = 0;
        $related_types = ['page', 'post', 'video'];
        for ($related_id = 1; $related_id <= 10; $related_id ++) {
            for ($num = 1; $num <= 3; $num ++) {
                foreach ($related_types as $related_type) {
                    $comment_id ++;
                    $body = "Comment {$num} on {$related_type} {$related_id}";
                    $this->connection->perform($stm, [
                        $comment_id, $related_type, $related_id, $body
                    ]);
                }
            }
        }
    }

    protected function employee()
    {
        $this->connection->query("CREATE TABLE employee (
            id       INTEGER PRIMARY KEY AUTOINCREMENT,
            name     VARCHAR(10) NOT NULL UNIQUE,
            building INTEGER,
            floor    INTEGER
        )");

        $stm = "INSERT INTO employee (name, building, floor) VALUES (?, ?, ?)";
        $rows = [
            ['Anna',  1, 1],
            ['Betty', 1, 2],
            ['Clara', 1, 3],
            ['Donna', 1, 1],
            ['Edna',  1, 2],
            ['Fiona', 1, 3],
            ['Gina',  2, 1],
            ['Hanna', 2, 2],
            ['Ione',  2, 3],
            ['Julia', 2, 1],
            ['Kara',  2, 2],
            ['Lana',  2, 3],
        ];
        foreach ($rows as $row) {
            $this->connection->perform($stm, $row);
        }
    }

    protected function authors()
    {
        $this->connection->query("CREATE TABLE authors (
            author_id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(10) NOT NULL
        )");

        $stm = "INSERT INTO authors (name) VALUES (?)";
        $rows = [
            ['Anna'],
            ['Betty'],
            ['Clara'],
            ['Donna'],
            ['Edna'],
            ['Fiona'],
            ['Gina'],
            ['Hanna'],
            ['Ione'],
            ['Julia'],
            ['Kara'],
            ['Lana'],
        ];
        foreach ($rows as $row) {
            $this->connection->perform($stm, $row);
        }
    }

    protected function tags()
    {
        $this->connection->query("CREATE TABLE tags (
            tag_id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(10) NOT NULL
        )");

        $stm = "INSERT INTO tags (name) VALUES (?)";
        $rows = [
            ['foo'],
            ['bar'],
            ['baz'],
            ['dib'],
            ['zim'],
        ];
        foreach ($rows as $row) {
            $this->connection->perform($stm, $row);
        }
    }

    protected function threads()
    {
        $this->connection->query("CREATE TABLE threads (
            thread_id INTEGER PRIMARY KEY AUTOINCREMENT,
            author_id INTEGER NOT NULL,
            subject VARCHAR(255) NOT NULL,
            body TEXT NOT NULL
        )");

        $stm = "INSERT INTO threads (author_id, subject, body) VALUES (?, ?, ?)";
        for ($i = 0; $i < 20; $i ++) {
            $author_id = $i % 4 + 1; // first 4 people have 5 threads each
            $thread_id = $i + 1;
            $subject = "Thread subject {$thread_id}";
            $body = "Thread body {$thread_id}";
            $this->connection->perform($stm, [$author_id, $subject, $body]);
        }
    }

    protected function summaries()
    {
        $this->connection->query("CREATE TABLE summaries (
            summary_id INTEGER PRIMARY KEY AUTOINCREMENT,
            thread_id INTEGER NOT NULL,
            reply_count INTEGER NOT NULL DEFAULT 0,
            view_count INTEGER NOT NULL DEFAULT 0
        )");

        $stm = "INSERT INTO summaries (thread_id) VALUES (?)";
        for ($i = 0; $i < 20; $i ++) {
            $thread_id = $i + 1;
            $this->connection->perform($stm, [$thread_id]);
        }
    }

    protected function taggings()
    {
        $this->connection->query("CREATE TABLE taggings (
            tagging_id INTEGER PRIMARY KEY AUTOINCREMENT,
            thread_id INTEGER,
            tag_id INTEGER
        )");

        // add 3 tags to each thread except thread #3
        $stm = "INSERT INTO taggings (thread_id, tag_id) VALUES (?, ?)";
        for ($i = 0; $i < 20; $i ++) {
            $thread_id = $i + 1;
            if($thread_id == 3) {
                continue;
            }
            $tags = [
                (($i + 0) % 5) + 1,
                (($i + 1) % 5) + 1,
                (($i + 2) % 5) + 1,
            ];
            foreach ($tags as $tag_id) {
                $this->connection->perform($stm, [$thread_id, $tag_id]);
            }
        }
    }

    protected function replies()
    {
        $this->connection->query("CREATE TABLE replies (
            reply_id INTEGER PRIMARY KEY AUTOINCREMENT,
            thread_id INTEGER NOT NULL,
            author_id INTEGER NOT NULL,
            body TEXT
        )");

        // add 5 replies to each thread
        $stm = "INSERT INTO replies (thread_id, author_id, body) VALUES (?, ?, ?)";
        for ($thread_id = 1; $thread_id <= 20; $thread_id ++) {
            for ($i = 0; $i <= 4; $i ++) {
                $author_id = (($thread_id + $i) % 10) + 1;
                $reply_no = $i + 1;
                $body = "Reply {$reply_no} on thread {$thread_id}";
                $this->connection->perform($stm, [$thread_id, $author_id, $body]);
                $this->connection->perform("
                    UPDATE summaries
                    SET reply_count = reply_count + 1
                    WHERE thread_id = {$thread_id}
                ");
            }
        }
    }

    public function bidifoos()
    {
        $this->connection->query("CREATE TABLE bidifoos (
            bidifoo_id INTEGER PRIMARY KEY AUTOINCREMENT,
            bidibar_id INTEGER,
            name VARCHAR(10)
        )");
    }

    public function bidibars()
    {
        $this->connection->query("CREATE TABLE bidibars (
            bidibar_id INTEGER PRIMARY KEY AUTOINCREMENT,
            bidifoo_id INTEGER,
            name VARCHAR(10)
        )");

        $this->connection->perform("INSERT INTO bidibars (name) VALUES ('prebar')");
    }

    public function nopkeys()
    {
        $this->connection->query("CREATE TABLE nopkeys (
            name VARCHAR(255),
            email VARCHAR(255)
        )");
    }
}
