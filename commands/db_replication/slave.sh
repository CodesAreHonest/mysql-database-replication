#!/usr/bin/env bash

if [[ $# -eq 0 ]] ; then
    echo 'Please provide mysql binary file name.'
    exit 1
fi

## 4. Set Replication

for N in 1 2
  do docker exec -it ambc_slave_db_$N mysql -uroot -psecurerootpassword \
    -e "CHANGE MASTER TO MASTER_HOST='ambc_master_db', MASTER_USER='repl', \
      MASTER_PASSWORD='slavepass', MASTER_LOG_FILE='binlog.$1';"

  docker exec -it ambc_slave_db_$N mysql -uroot -psecurerootpassword -e "START SLAVE;"
done


## 5. Show Replication Status

for N in 1 2
  do docker exec -it ambc_slave_db_$N mysql -uroot -psecurerootpassword -e "SHOW SLAVE STATUS\G"
done 