#!/usr/bin/env bash

show_help() {
  name="terminus"
  description="Runs a terminus command on all sites, in the given environment."
  usage="scripts/fleet terminus [env] [command] [options]"
  # Use this exact template in all show_help functions for consistentency.
  . ${BASEDIR}/scripts/.fleet/templates/show_help.sh
}

do_command() {
  for site in $sites
  do
    alias="jcc-${site}.${env}"

    echo -e "\n${B}Running ${RE}terminus ${alias} ${command} ${@}"
    terminus $command $alias $@
  done
}

case $1 in

  --help|-h|-?)
    show_help
    ;;
  *)
    env=$1
    command=$2
    shift 2

    if [[ -z "$env" ]] || [[ -z "$command" ]] ; then
      echo -e "\n${R}You must enter a valid [environment] and [command].${RE}"
      show_help
      exit 1
    fi
    do_command $@
    ;;

esac
