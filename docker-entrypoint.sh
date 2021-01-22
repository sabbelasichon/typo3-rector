#!/bin/sh

isCommand() {
  if [ "$1" = "sh" ]; then
    return 1
  fi

  typo3-rector help "$1" > /dev/null 2>&1
}

# check if the first argument passed in looks like a flag
if [ "${1#-}" != "$1" ]; then
  set -- /sbin/tini -- typo3-rector "$@"
# check if the first argument passed in is typo3-rector
elif [ "$1" = 'typo3-rector' ]; then
  set -- /sbin/tini -- "$@"
# check if the first argument passed in matches a known command
elif isCommand "$1"; then
  set -- /sbin/tini -- typo3-rector "$@"
fi

exec "$@"
