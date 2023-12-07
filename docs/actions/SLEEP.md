# SLEEP command
This command pauses all `token` `ACTIONS` until `RESUME_BLOCK` is reached

## PARAMS
| Name           | Type   | Description                                  |
| -------------  | ------ | -------------------------------------------- |
| `VERSION`      | String | Broadcast Format Version                     |
| `TICK`         | String | 1 to 250 characters in length                |
| `RESUME_BLOCK` | String | Block index to resume CITNS `ACTION` commands |

## Formats

### Version `0`
- `VERSION|TICK|RESUME_BLOCK`

## Examples
```
bt:SLEEP|0|COSS|791495`
This example pauses/sleeps ALL CITNS `ACTION` commands on COSS `token` until block 791495
```

## Rules

## Notes
- USE WITH CAUTION! `SLEEP` will stop/pause all CITNS actions, including dispenses.
- `SLEEP` can result in loss of user funds, as payments to CITNS dispensers will be ignored.
- Can use `LOCK_SLEEP` in `ISSUE` command to prevent `SLEEP` command
- Can issue a `SLEEP` before `RESUME_BLOCK` to extend a `SLEEP`
