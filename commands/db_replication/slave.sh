#!/usr/bin/env bash


## 4. Set Replication

for N in 1 2
  do docker exec -it ambc_slave_db_$N mysql -uroot -psecurerootpassword \
    -e "CHANGE MASTER TO MASTER_HOST='ambc_slave_db', MASTER_USER='repl', \
      MASTER_PASSWORD='slavepass', MASTER_LOG_FILE='$0';"

  docker exec -it ambc_slave_db_$N mysql -uroot -psecurerootpassword -e "START SLAVE;"
done


## 5. Show Replication Status

for N in 1 2
  do docker exec -it ambc_slave_db_$N mysql -uroot -psecurerootpassword -e "SHOW SLAVE STATUS\G"
  do docker exec -it ambc_slave_db_$N mysql -uroot -psecurerootpassword -e "SHOW SLAVE STATUS\G"
done 