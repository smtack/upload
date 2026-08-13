<?php

namespace Models;

use Core\Database;

class Upload
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function newUpload(array $fields = [])
    {
        if ($this->db->insert('uploads', $fields)) {
            return true;
        }

        return false;
    }

    public function getUploadsByDate()
    {
        $rows = $this->db->query('select * from uploads left join users on uploads.upload_by = users.user_id order by upload_date desc');

        if($rows->count()) {
            return $rows->results();
        }

        return false;
    }

    public function getUploadsByViews()
    {
        $rows = $this->db->query('select * from uploads left join users on uploads.upload_by = users.user_id order by upload_views desc');

        if($rows->count()) {
            return $rows->results();
        }

        return false;
    }

    public function getUploadsByStarRating()
    {
        $rows = $this->db->query("
            SELECT
                u.*,
                user.*,
                COALESCE(AVG(r.rating_number), 0) AS avg_rating,
                COUNT(r.rating_number) AS rating_count
            FROM
                uploads u
            LEFT JOIN
                uploads_ratings r
            ON
                u.upload_id = r.rating_upload
            LEFT JOIN
                users user
            ON
                u.upload_by = user.user_id
            GROUP BY
                u.upload_id, user.user_id
            ORDER BY
                avg_rating DESC, rating_count DESC
            ");

        if($rows->count()) {
            return $rows->results();
        }

        return false;
    }

    public function getHomepageUploads(string|int $user)
    {
        $rows = $this->db->query("
            SELECT
                *
            FROM
                uploads
            LEFT JOIN
                users
            ON
                users.user_id = uploads.upload_by
            WHERE
                (upload_by = users.user_id AND users.user_id = ?)
            OR
                (upload_by = users.user_id AND upload_by
                IN
                  (SELECT
                    follow_following
                  FROM
                    follows
                  WHERE
                    follow_user = ?))
            ORDER BY
                upload_date
            DESC", [$user, $user]);

        if($rows->count()) {
            return $rows->results();
        }

        return false;
    }

    public function getUsersUploads(string|int $user)
    {
        $rows = $this->db->query("select * from uploads left join users on uploads.upload_by = users.user_id where upload_by = ? order by upload_date desc", [$user]);

        return $rows->results();
    }

    public function getUpload(int $id)
    {
        $row = $this->db->query("select * from uploads left join users on uploads.upload_by = users.user_id where upload_id = ?", [$id]);

        if($row->count()) {
            return $row->first();
        }

        return false;
    }

    public function addView(int $id)
    {
        $row = $this->db->query("update uploads set upload_views = upload_views + 1 where upload_id = ?", [$id]);

        return true;
    }

    public function rate(array $data)
    {
        if ($rating = $this->getUsersRating($data['rating_user'], $data['rating_upload'])) {
            if ($this->db->update('uploads_ratings', $data, 'rating_id', $rating->rating_id)) {
                return true;
            }
        } else {
            if ($this->db->insert('uploads_ratings', $data)) {
                return true;
            }
        }

        return false;
    }

    public function getUploadRating(int $upload)
    {
        $row = $this->db->query("
            SELECT
                uploads.upload_id,
                AVG(uploads_ratings.rating_number)
            AS
                rating
            FROM
                uploads
            LEFT JOIN
                uploads_ratings
            ON
                uploads.upload_id = uploads_ratings.rating_upload
            WHERE
                uploads.upload_id = ?
            GROUP BY
                uploads.upload_id
        ", [$upload]);

        if ($row->count()) {
            return $row->first();
        }

        return false;
    }

    public function getUsersRating(int $user, int $upload)
    {
        $row = $this->db->query("
            SELECT
                rating_number,
                rating_id
            FROM
                uploads_ratings
            WHERE
                rating_user = ?
            AND
                rating_upload = ?
        ", [$user, $upload]);

        if ($row->count()) {
            $rated = $row->first();

            return $rated ? $rated : false;
        }
    }

    public function editUpload(int $id, array $data = [])
    {
        if ($this->db->update('uploads', $data, 'upload_id', $id)) {
            return true;
        }

        return false;
    }

    public function deleteUpload(int $id)
    {
        if ($this->db->delete('uploads', ['upload_id' => $id])) {
            return true;
        }

        return false;
    }

    public function favorite(array $favorite)
    {
        if($this->db->insert('favorites', $favorite)) {
            return true;
        }

        return false;
    }

    public function unfavorite(array $favorite)
    {
        if ($this->db->query("DELETE FROM favorites WHERE favorite_user = ? AND favorite_upload = ?", [$favorite['favorite_user'], $favorite['favorite_upload']])) {
            return true;
        }

        return false;
    }

    public function getFavoritesData($favorite)
    {
        $rows = $this->db->query("SELECT * FROM favorites WHERE favorite_upload = ?", [$favorite]);

        $results = $rows->results();

        return array_map(fn ($result) => (array) $result, $results);
    }

    public function getUsersFavorites(int $user)
    {
        $rows = $this->db->query("
            SELECT
                *
            FROM
                uploads
            LEFT JOIN
                favorites
            ON
                uploads.upload_id = favorites.favorite_upload
            LEFT JOIN
                users
            ON
                uploads.upload_by = users.user_id
            WHERE
                favorite_user = $user
            ORDER BY
                upload_date
            DESC
        ");

        if ($rows->count()) {
            return $rows->results();
        }

        return false;
    }

    public function newComment(array $comment = [])
    {
        if($this->db->insert('comments', $comment)) {
            return true;
        }

        return false;
    }

    public function getComments(int $id)
    {
        $comments = $this->db->query("SELECT * FROM comments LEFT JOIN users ON comments.comment_by = users.user_id WHERE comment_upload = ?", [$id]);

        if($comments->count()) {
            return $comments->results();
        }

        return false;
    }

    public function getComment(int $id)
    {
        $comment = $this->db->query("SELECT * FROM comments LEFT JOIN users ON comments.comment_by = users.user_id WHERE comment_id = ?", [$id]);

        if($comment->count()) {
            return $comment->first();
        }

        return false;
    }

    public function editComment(int $id, array $data = [])
    {
        if($this->db->update('comments', $data, 'comment_id', $id)) {
            return true;
        }

        return false;
    }

    public function deleteComment(int $id)
    {
        if($this->db->delete('comments', ['comment_id' => $id])) {
            return true;
        }

        return false;
    }

    public function searchUploads(string $keywords)
    {
        $results = $this->db->query("SELECT * FROM uploads LEFT JOIN users ON uploads.upload_by = users.user_id WHERE upload_title LIKE ?", ["%$keywords%"]);

        if($results->count()) {
            return $results->results();
        }

        return false;
    }
}
