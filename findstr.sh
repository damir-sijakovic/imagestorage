#!/bin/bash
if [[ $# -eq 0 ]] ; then
    echo 'USAGE: findstr.sh <string>'
    exit 0
fi
find . -type f -print | xargs grep $1
