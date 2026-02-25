import re
import sys

import pandas as pd


fn_in = sys.argv[1] if len(sys.argv) > 1 else None
if fn_in is None:
    raise ValueError("Usage: python convert-csv.py <input_csv_file>")

df = pd.read_csv(fn_in)

LAT_TEXT_COL = "Latitud Inici (format a decidir)"
LON_TEXT_COL = "Longitud Inici (format a decidir)"

pattern = re.compile(r"\s*(\d+)°(\d+)'(\d+(?:\.\d+)?)\"([NSEW])\s*")

def dms_to_decimal(value: str):
    if not isinstance(value, str):
        return None
    m = pattern.match(value)
    if not m:
        return None
    deg, minute, sec, hemi = m.groups()
    dec = float(deg) + float(minute)/60 + float(sec)/3600
    if hemi in ["S", "W"]:
        dec = -dec
    return dec

df["start_latitude"] = df[LAT_TEXT_COL].apply(dms_to_decimal)
df["start_longitude"] = df[LON_TEXT_COL].apply(dms_to_decimal)

it_col = "itinerary_name"
pos_col = "position"
df = df.sort_values([it_col, pos_col])

df["end_latitude"] = df.groupby(it_col)["start_latitude"].shift(-1)
df["end_longitude"] = df.groupby(it_col)["start_longitude"].shift(-1)

df = df.drop(columns=[LAT_TEXT_COL, LON_TEXT_COL])

fn_out = sys.argv[2] if len(sys.argv) > 2 else "output.csv"
df.to_csv(fn_out, index=False)
print("Desat a:", fn_out)
