@echo off

for /f "skip=3 tokens=1" %%f in ('tasklist') do (
    echo %%f
)