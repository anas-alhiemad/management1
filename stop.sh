#!/bin/bash

# File that contains the process IDs
PID_FILE="run_pids.txt"

if [ -f $PID_FILE ]; then
    while IFS= read -r pid
    do
        echo "Attempting to stop process with PID: $pid"
        if kill -0 $pid > /dev/null 2>&1; then
            echo "Stopping process with PID: $pid"
            kill $pid
            if [ $? -eq 0 ]; then
                echo "Process with PID: $pid stopped successfully"
            else
                echo "Failed to stop process with PID: $pid"
            fi
        else
            echo "Process with PID: $pid is not running"
        fi
    done < "$PID_FILE"

    rm $PID_FILE
    echo "All processes stopped."
else
    echo "PID file not found. Are the processes running?"
fi
