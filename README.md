# pfelk
tinkering with pfELK - adapted files only

Before you replace any of your original files -> ! make a backup copy
The output file 50-outputs.pfelk file contains a password you need to copy paste into this new and adapted 50-outputs.pfelk file. Line 57!


For the full repo, look at https://github.com/pfelk/pfelk

I have adapted pfELK to also receive pfblockerng logs from pfSense as well as interfaces and gateway monitoring data from pfSense itself.

pfBlockerNG Solution:
https://github.com/pfelk/pfelk/discussions/486#discussion-5359284

pfSense Interface and Gateway Monitoring:
https://github.com/pfelk/pfelk/discussions/487#discussion-5376866

Updates:
2023-07-09: I created a dashboard to view the most relevant pfBlockerNG data-stream elements. I have exported my dashboard not including related items. Hope this works for you when importing. Let me know if I have to share it in another way.
