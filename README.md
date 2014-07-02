EZRide - CS307 Project at Purdue
================================

The project my team created in CS307 at Purdue in the Fall of 2013. The project aimed to create a service that allowed users
to create groups and share their calendars. This would allow the users to more easily schedule events and outings involving
other people. It also aimed to provide a map view that would allow users to see the most recent known location of the other
users in the group. This would allow users to spontaneously plan events (things like "Hey, I see you're close to me. Let's
meet for lunch.").

The project consisted of a server, web client, and an Android client. The server and the web client were pretty much one in
the same, and were written in PHP, HTML, CSS, and JS, and were hosted on Openshift. The Android client was targeted at API
19 (4.4.x, KitKat), but would work on any API above level 14 (4.0.x, IceCreamSandwich). It was designed using the most
up-to-date apis and design standards (utilizing fragments instead of activities where possible and where they made sense,
for example).

As a fair bit of warning, there are some missing features as the project was never fully completed. Google+ login was
successfully implemented in both clients and the server, along with our custom login option, but Facebook login was never
finished.

As a last note, I was responsible for the database, the Android client, and any server side files needed to allow the
Android client to communicate with the server. One other team member worked with me on said items, while the other three
members worked on the server backend as well as the web client.

Building/Running the Project
============================

For the web server and web client, you'll need to go through the project and add any of the required keys, ids, secrets, etc
in order to allow the server and client to access certain APIs (Google, Facebook, etc) and make sure the individual files
can actually communicate with each other. In order to make it easy, you'll have to search the files for YOUR_KEY_HERE,
YOUR_ID_HERE, YOUR_SECRET_HERE, and YOUR_DOMAIN_HERE. If you want to set it up pretty much as-is, you'll need to set up a
server on Openshift, create a MySQL database called "ezride" and use the SQL file in the root directory to initialize the
necessary tables. Otherwise, you'll have to go through the project and modify it in order to support the environment it will
be running in.

As for the Android client, if you want to use it as is, you'll need to use Eclipse. Once it is imported, you'll probably have
to fix the library references and whatnot. I have not tried to import it into Android Studio, so that route will be an
adventure for you if you choose to use it. Regardless, you'll have to go through the files and search for YOUR_KEY_HERE,
YOUR_ID_HERE, YOUR_SECRET_HERE, and YOUR_DOMAIN_HERE and add what's required.
