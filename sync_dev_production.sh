#!/bin/bash

# ==============================================================================
# DiveForge Sync Script
#
# A helper script to synchronize files between the local development environment
# and a remote server using rsync.
#
# USAGE:
#   Place this script in your project root (e.g., ~/Developer/DiveForge/).
#   Make it executable: chmod +x sync.sh
#
#   To PUSH changes to the remote server:
#   ./sync.sh push
#
#   To PULL changes from the remote server:
#   ./sync.sh pull
#
# ==============================================================================

# --- Script Configuration ---
# Set the remote user and host.
REMOTE_USER="williamnash"
REMOTE_HOST="lori-lan"

# Set the source and destination directories.
# The script assumes it's being run from the local project root.
LOCAL_DIR="./"
REMOTE_DIR="/var/www/html/DiveForge/"

# The name of the file containing patterns to exclude during a PUSH.
# This file should exist in your local project root.
PUSH_EXCLUDE_FILE=".rsync-exclude"


# --- Helper Functions ---

# Function to display usage information and exit.
usage() {
    echo "Usage: $0 [push|pull]"
    echo "  push: Syncs local changes TO the remote server (${REMOTE_HOST})."
    echo "  pull: Syncs remote changes FROM the remote server to local."
    exit 1
}

# Function to sync local files TO the remote server.
sync_to_server() {
    echo "🚀 Pushing local changes to ${REMOTE_USER}@${REMOTE_HOST}..."
    echo "---------------------------------------------------------"

    # Check if the exclude file exists before trying to use it.
    if [ ! -f "$PUSH_EXCLUDE_FILE" ]; then
        echo "❌ Error: Push exclude file '$PUSH_EXCLUDE_FILE' not found."
        echo "Please ensure the file exists in your project root before pushing."
        exit 1
    fi

    # This command syncs your local directory to the remote server.
    # It requires sudo locally to run, and it uses --rsync-path to run rsync
    # with sudo on the remote server, which is necessary to write to /var/www/html
    # and change file ownership.
    sudo rsync -avz \
        --exclude-from="$PUSH_EXCLUDE_FILE" \
        --chown=apache:apache \
        -e "ssh" \
        --rsync-path="sudo rsync" \
        "$LOCAL_DIR" \
        "${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_DIR}"

    echo "---------------------------------------------------------"
    echo "✅ Push complete."
}

# Function to sync remote files FROM the server to your local machine.
sync_from_server() {
    echo "📥 Pulling remote changes from ${REMOTE_USER}@${REMOTE_HOST}..."
    echo "----------------------------------------------------------"

    # This command syncs the remote directory back to your local machine.
    # It explicitly excludes files and directories you typically wouldn't want to
    # overwrite locally, like your .env file, dependencies, and git history.
    rsync -avz \
        --exclude=".env" \
        --exclude="vendor/" \
        --exclude="node_modules/" \
        --exclude="storage/" \
        --exclude=".git/" \
        --exclude="public/hot" \
        "${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_DIR}" \
        "$LOCAL_DIR"

    echo "----------------------------------------------------------"
    echo "✅ Pull complete."
}


# --- Main Script Logic ---

# Check if at least one argument was provided.
if [ -z "$1" ]; then
    echo "❌ Error: No command provided."
    usage
fi

# Process the command provided by the user.
case "$1" in
    push)
        sync_to_server
        ;;
    pull)
        sync_from_server
        ;;
    *)
        echo "❌ Error: Invalid command '$1'."
        usage
        ;;
esac

exit 0
