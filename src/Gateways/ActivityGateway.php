<?php
namespace ProgWeb\TodoWeb\Gateways;

class ActivityGateway {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function get($activityId) {

        $statement = "
        SELECT *
        FROM activities WHERE id = :activity_id
        ";

        $statement = $this->db->prepare($statement);
        $statement->execute([
            'activity_id' => $activityId,
        ]);
        $parsed_data = array_map(array($this, 'mapActivities'), $statement->fetchAll(\PDO::FETCH_ASSOC));
        return $parsed_data;
    }

    public function list($userId) {

        $statement = "
        SELECT *
        FROM activities WHERE user_id = :user_id
        ";

        $statement = $this->db->prepare($statement);
        $statement->execute([
            'user_id' => $userId,
        ]);
        $parsed_data = array_map(array($this, 'mapActivities'), $statement->fetchAll(\PDO::FETCH_ASSOC));
        return json_encode($parsed_data);
    }

    public function insert($userId, Array $input)
    {
        $statement = "
        INSERT INTO activities
        (user_id, title, description, due_date)
        VALUES
        (:user_id, :title, :description, :due_date);
        ";

        $statement = $this->db->prepare($statement);
        $statement->execute(array(
            'user_id' => $userId,
            'title' => $input['title'],
            'description'  => $input['description'],
            'due_date' => $input['due_date'],
        ));
        return $statement->rowCount();
    }

    public function update($id, Array $input)
    {
        $statement = "
        UPDATE activities
        SET
        title = :title, description = :description, due_date = :due_date
        WHERE id = :id
        ";

        $statement = $this->db->prepare($statement);
        $statement->execute(array(
            'id' => $id,
            'title' => $input['title'],
            'description'  => $input['description'],
            'due_date' => $input['due_date'],
        ));
        return $statement->rowCount();
    }

    public function delete($id)
    {
        $statement = "
        DELETE FROM activities
        WHERE id = :id
        ";

        $statement = $this->db->prepare($statement);
        $statement->execute(array(
            'id' => $id,
        ));
        return $statement->rowCount();
    }

    private function mapActivities($activity) {
        $activity['is_complete'] = $activity['is_complete'] ? true : false;
        return $activity;
    }
}
