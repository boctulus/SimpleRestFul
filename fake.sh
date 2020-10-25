#!/bin/bash

for i in {1..5}; do
    ./com.sh dumb create_bulk &
    sleep 2	
done



