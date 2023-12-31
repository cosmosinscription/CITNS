# MINT command
This command mints `CITNS` `token` supply

## PARAMS
| Name          | Type   | Description                            |
| ------------- | ------ | -------------------------------------- |
| `VERSION`     | String | Broadcast Format Version               |
| `TICK`        | String | 1 to 250 characters in length          |
| `AMOUNT`      | String | Amount of `tokens` to mint             |
| `DESTINATION` | String | Address to transfer minted `tokens` to |

## Formats

### Version `0`
- `VERSION|TICK|AMOUNT|DESTINATION`

## Examples
```
bt:MINT|0|COSS|1
This example mints 1 COSS `token` to the broadcasting address
```

```
bt:MINT|0|BRRR|10000000000000|cosmos1f0wvhastnvu3ynyspvv8de3rpe66jmhwtthdrv
This example mints 10,000,000,000,000 BRRR tokens and transfers them to cosmos1f0wvhastnvu3ynyspvv8de3rpe66jmhwtthdrv 
```

## Rules
- `broadcast` `status` must be `valid`
- `token` supply may be minted until `MAX_SUPPLY` is reached.
- Transactions that attempt to mint supply beyond `MAX_SUPPLY` shall be considered invalid and ignored.

## Notes
