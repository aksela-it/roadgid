#!/bin/bash

dir1=$HOME"/Документы/projects/roadgid.aksela.ru/"
dir2="dev@elnar.cryptoactivo.ru:/home/dev/roadgid.aksela.ru/"

rsync -a --progress --exclude='.git' $dir1 $dir2
