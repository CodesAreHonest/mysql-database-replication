#!/usr/bin/env bash

if [[ $# -eq 0 ]] ; then
    echo 'Please provide mysql binary file name.'
    exit 1
fi

# 4. Set Replication

for N in 1 2
  do docker exec -it ambc_slave_db_$N mysql -uroot -psecurerootpassword -e "RESET SLAVE;"
  
  docker exec -it ambc_slave_db_$N mysql -uroot -psecurerootpassword \
    -e "CHANGE MASTER TO MASTER_HOST='ambc_master_db', MASTER_USER='repl', \
      MASTER_PASSWORD='slavepass', MASTER_LOG_FILE='binlog.$1';"

  docker exec -it ambc_slave_db_$N mysql -uroot -psecurerootpassword -e "START SLAVE;"

done

## 5. Enable Logs

for N in 1 2
  do docker exec -it ambc_slave_db_$N mysql ambc -uroot -psecurerootpassword -e "SET GLOBAL general_log = 'ON';
SET GLOBAL slow_query_log = 'on';
SET GLOBAL long_query_time = 1; 
SET GLOBAL log_output = 'table';";  
done

## 6. Show Replication Status

for N in 1 2
  do docker exec -it ambc_slave_db_$N mysql -uroot -psecurerootpassword -e "SHOW SLAVE STATUS\G"
done 