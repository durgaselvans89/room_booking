## API for Room booking
Created Three API's to book a Room 
1. Book a room
2. Display all bookings
3. Cancel booked rooms


----

##### Room booking Module
1. [x] Book your meeting
1. [x] Display all meetings
1. [x] Cancel meetings 


### How to Run:
1. Clone Project - Place all the files in Webserver and start apache
2. Create a database called - `room_allocation` and import the attached sql file.
3. Test with postman with below documentation.


### Test
Test with Postman -

1. Book A room
This API will accept the meeting room,start time and end time. It will reserve the room for everyone.
http://localhost/room_booking/service/RoomService.php?service_name=book_room

Input parameters

1. title=A(Meeting room Name)
2. start_at=2022-11-08 10:00:00(meeting start time)
3. end_at=2022-11-08 10:00:00 (meeting end time)


2. List a booking

It will list all the slots booked for a week

http://localhost/room_booking/service/RoomService.php?service_name=list_room

Input parameters - None

3. Cancel booking

It will allow you cancel the meeting.

http://localhost/room_booking/service/RoomService.php?service_name=cancel_room

Input parameters - 

1. title=A(Meeting room Name)
2. start_at=2022-11-08 10:00:00(meeting start time)



