# SEND command
This command sends/transfers one or more `token`s between addresses


## PARAMS
| Name          | Type   | Description                     |
| ------------- | ------ | ------------------------------- |
| `VERSION`     | String | Broadcast Format Version        |
| `TICK`        | String | 1 to 250 characters in length   |
| `AMOUNT`      | String | Amount of `tokens` to send      |
| `DESTINATION` | String | Address to transfer `tokens` to |
| `MEMO`        | String | An optional memo to include     |

## Formats

### Version `0`
- `VERSION|TICK|AMOUNT|DESTINATION|MEMO`

### Version `1`
- `VERSION|TICK|AMOUNT|DESTINATION|AMOUNT|DESTINATION|MEMO`

### Version `2`
- `VERSION|TICK|AMOUNT|DESTINATION|TICK|AMOUNT|DESTINATION|MEMO`

### Version `3`
- `VERSION|TICK|AMOUNT|DESTINATION|MEMO|TICK|AMOUNT|DESTINATION|MEMO`


## Examples
```
bt:SEND|0|COSS|1|cosmos1f0wvhastnvu3ynyspvv8de3rpe66jmhwtthdrv
This example sends 1 COSS token to cosmos1f0wvhastnvu3ynyspvv8de3rpe66jmhwtthdrv
```

```
bt:SEND|0|BRRR|5|cosmos1f0wvhastnvu3ynyspvv8de3rpe66jmhwtthdrv|CITNS is Awesome
This example sends 5 BRRR tokens to cosmos1f0wvhastnvu3ynyspvv8de3rpe66jmhwtthdrv with a memo
```

```
bt:SEND|1|BRRR|5|cosmos1f0wvhastnvu3ynyspvv8de3rpe66jmhwtthdrv|1|1BoogrfDADPLQpq8LMASmWQUVYDp4t2hF9
This example sends 5 BRRR tokens to cosmos1f0wvhastnvu3ynyspvv8de3rpe66jmhwtthdrv and 1 BRRR token to 1BoogrfDADPLQpq8LMASmWQUVYDp4t2hF9
```

```
bt:SEND|2|BRRR|5|cosmos1f0wvhastnvu3ynyspvv8de3rpe66jmhwtthdrvCOSS1|1BoogrfDADPLQpq8LMASmWQUVYDp4t2hF9|CITNS is Awesome
This example sends 5 BRRR tokens to cosmos1f0wvhastnvu3ynyspvv8de3rpe66jmhwtthdrv and 1 TEST token to 1BoogrfDADPLQpq8LMASmWQUVYDp4t2hF9 with a memo
```

## Rules
- A `token` transfer shall only be considered valid if the broacasting address has balances of the `token` to cover the transfer `AMOUNT`
- A `token` transfer that does _not_ have `AMOUNT` in the broadcasting address shall be considered invalid and ignored.
- A valid `token` transfer will deduct the `token` `AMOUNT` from the broadcasting addresses balances
- A valid `token` tranfer will credit the `token` `AMOUNT` to the `DESTINATION` address or addresses
- `MEMO` characters **NOT** allowed are :
   - pipe `|` (used as field separator)
   - semicolon `;` (used as command separator)

## Notes
- `TRANSFER` `ACTION` can be used for backwards-compatability with BRC20/SRC20 `TRANSFER`
- Format version `0` allows for a single send
- Format version `1` allows for repeating `AMOUNT` and `DESTINATION` params to enable multiple transfers
- Format version `2` allows for repeating `TICK`, `AMOUNT` and `DESTINATION` params to enable multiple transfers
- Format version `3` allows for repeating `TICK`, `AMOUNT`, `DESTINATION`, and `MEMO` params to enable multiple transfers
- Format version `0`, `1`, and `2` allow for a single optional `MEMO` field to be included as the last PARAM

