#!/usr/bin/env bash
$SOURCE_PATH=~/Downloads/DonationProfiles #replace to match yours
$DESTINATION_PATH=t@10.0.1.16:/home/t/projects/asha/ashadonations/DonationProfiles #replace to match yours
rsync -avzheR --dry-run -e "ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null" $SOURCE_PATH $DESTINATION_PATH
