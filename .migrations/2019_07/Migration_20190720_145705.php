<?php


use Dolphin\Commands\Migration\BaseMigration;


class Migration_20190720_145705 extends BaseMigration
{
    /**
     * @throws \Exception
     */
    public function up()
    {
        try {

            $this->db->beginTransaction();
            $table = $this->db->table('users');

            $table->insert([
                'id'         => 1,
                'email'      => 'admin@example.com',
                'username'   => 'admin',
                'password'   => '$2y$10$kIYw93wFRcV88nHzyYVpQu9w7YnU0FOBl4Gt.SVVsZozIlBJ075t6', // admin
                'created_at' => '2019-01-01 00:00:00',
                'updated_at' => null,
                'verified'   => 1,
            ]);

            $this->db->commit();

        } catch (Exception $e) {

            $this->console->write($e->getMessage(), \Dolphin\Console::RED);
            $this->db->rollBack();
            throw $e;

        }

    }


    /**
     * @throws \Exception
     */
    public function down()
    {
        throw new Exception('This migration cannot be rolled back: '.__CLASS__.' ('.$this->description().')');
    }


    public function description()
    {
        return 'Add default administrator';
    }
}
