#!/bin/bash

# File to store the process IDs
PID_FILE="run_pids.txt"

# Function to start Laravel development server
start_server() {
    echo "Starting Laravel development server..."
    nohup php artisan serve --host=127.0.0.1 --port=8000 >> storage/logs/server.log 2>&1 &
    SERVER_PID=$!
    echo $SERVER_PID >> $PID_FILE
    echo "Laravel development server started with PID $SERVER_PID"
}

# Function to start the queue worker
start_worker() {
    echo "Starting Laravel queue worker..."
    nohup php artisan queue:work --sleep=3 --tries=3 >> storage/logs/queue.log 2>&1 &
    WORKER_PID=$!
    echo $WORKER_PID >> $PID_FILE
    echo "Laravel queue worker started with PID $WORKER_PID"
}

# Clean up any previous PID file
rm -f $PID_FILE

# Start the server and worker
start_server
start_worker

echo "Processes started. PIDs stored in $PID_FILE."
