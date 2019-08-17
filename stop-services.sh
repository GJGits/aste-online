#!/bin/bash

sudo docker container stop $(sudo docker container ls -aq)