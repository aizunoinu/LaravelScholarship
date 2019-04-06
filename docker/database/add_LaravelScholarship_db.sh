echo "CREATE DATABASE IF NOT EXISTS \`LaravelScholarship_db\` ;" | "${mysql[@]}"
echo "GRANT ALL ON \`LaravelScholarship_db\`.* TO '"$MYSQL_USER"'@'%' ;" | "${mysql[@]}"
echo 'FLUSH PRIVILEGES ;' | "${mysql[@]}"

"${mysql[@]}" < /docker-entrypoint-initdb.d/LaravelScholarship_db.sql_
