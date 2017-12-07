# borg-chart

> resisting the corporate hierarchy is futile, so we may as well make a nice UI for it
>
> – <cite>anonymous borg</cite>

This is a coding exercise and does almost nothing useful.

## "Features"

* sortable columns
* fuzzy search
* DataTables

## Notes

* This is over-engineered on purpose. I could've implemented this without Docker/Lando, without a caching layer, without any unit tests...but that wouldn't have demonstrated that I knew how to apply all that stuff, and wouldn't have been as fun!
* Conversely, there are plenty of cut corners for demo purposes. These are noted profusely in the comments.
* DataMapper (which I hadn't used before but which is _awesome_) offers a pagination feature out of the box so I threw that in there but locked it down to 100 per the spec.
* The way the distance computation integrates with the caching system is kinda wonky, and was designed to deliver constant-time distance lookup for incomplete sets of employees, i.e. when I was doing server-side search filtering. Then I found DataTables' search. If this were a real project I would've asked a lot more questions before designing such a feature but this is good enough for rock 'n' roll.

## Installation

### System Requirements

* [Lando](https://docs.devwithlando.io/)
* [Docker](https://www.docker.com/)

### Installation Steps

```
git clone git@github.com:acobster/borg-chart.git
cd borg-chart
lando start
lando db-import ./my-dump.sql
```
