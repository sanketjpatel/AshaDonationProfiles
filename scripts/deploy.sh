#!/usr/bin/env bash
#replace to match yours
SOURCE_PATH=~/Downloads/DonationProfiles
DESTINATION_PATH=t@10.0.1.16:/home/t/projects/asha/ashadonations/DonationProfiles
rsync -avzheR --dry-run -e "ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null" $SOURCE_PATH $DESTINATION_PATH
