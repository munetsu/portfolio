<?php

    class DATA{
        function __construct(){
            $this->pdo = $this->db_con();
        }

        //////////////////////////////////////////////
        // DB情報
        //////////////////////////////////////////////
        public function db_con(){
            $dbname = 'portfolio';
            $id = 'root';
            $pw = '';
            try{
                $pdo = new PDO('mysql:dbname='.$dbname.';charset=utf8;host=localhost',$id,$pw);
            } catch (PDOException $e){
                exit('DbConnectError:'.$e->getMessage());
            }
            return $pdo;
        }

        public function queryError($stmt){
            //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
            $error = $stmt->errorInfo();
            exit("QueryError:".$error[2]);
        }

        //////////////////////////////////////////////
        // INSERT
        //////////////////////////////////////////////
        public function insert($table, $column, $values){
            // var_dump($column);
            // exit();
            $sql = "INSERT INTO $table ($column) VALUES($values)";
            // var_dump($sql);
            // exit();
            $stmt = $this->pdo->prepare($sql);
            $res = $stmt->execute();
            if($res === false){
                return $this->queryError($stmt);
            }
            return $res;
        }

        public function insertMulti($table, $values){
            // var_dump($column);
            // exit();
            $sql = "INSERT INTO $table VALUES $values";
            // var_dump($sql);
            // exit();
            $stmt = $this->pdo->prepare($sql);
            $res = $stmt->execute();
            if($res === false){
                return $this->queryError($stmt);
            }
            return $res;
        }

        

        //////////////////////////////////////////////
        // SELECT
        //////////////////////////////////////////////
        // select文(取得データが1件の場合)
        public function select($column, $table, $where){
            $sql = "SELECT $column FROM $table $where";
            $stmt = $this->pdo->prepare($sql);
            $res = $stmt->execute();
            if($res === false){
                $this->queryError($stmt);
            }else {
                $res = $stmt->fetch(PDO::FETCH_ASSOC);
                return $res;
            }
        }

        // select文(取得データが複数件の場合)
        public function selectAll($column, $table, $where){
            $sql = "SELECT $column FROM $table $where";
            $stmt = $this->pdo->prepare($sql);
            $res = $stmt->execute();
            if($res === false){
                $this->queryError($stmt);
            }else {
                $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $res;
            }
        }

        // select(INNER JOIN)
        public function selectInnerJoin($sql){
            $sql = $sql;
            $stmt = $this->pdo->prepare($sql);
            $res = $stmt->execute();
            if($res === false){
                $this->queryError($stmt);
            }else {
                $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $res;
            }

        }

        //////////////////////////////////////////////
        // UPDATE
        //////////////////////////////////////////////
        public function update($table, $values, $where){
            $sql = "UPDATE $table SET $values $where";
            $stmt = $this->pdo->prepare($sql);
            $res = $stmt->execute();
            if($res === false){
                return $this->queryError($stmt);
            }
        }

        //////////////////////////////////////////////
        // DELETE
        //////////////////////////////////////////////

    }