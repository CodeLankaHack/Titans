#include <SoftwareSerial.h>
#include "TinyGPS.h"
#include <Wire.h>
#include <SPI.h>

SoftwareSerial SIM900(7, 8); // configure software serial port

TinyGPS gps;

#define GPS_TX_DIGITAL_OUT_PIN 3
#define GPS_RX_DIGITAL_OUT_PIN 4

long startMillis;
long secondsToFirstLocation = 0;

#define DEBUG

float latitude = 0.0;
float longitude = 0.0;
const byte interruptPin = 2;
String purseID = "003";
boolean isMoneyWithdraw = false;
int isCheckPhone = 0;
int cPhone = 12; //to digital pin 12


void setup() {     
  
  SIM900.begin(19200);
  Serial.begin(19200); 
  Serial.println("power up" );
  delay(100);
  
  pinMode(cPhone, INPUT); 
  
  // Serial is GPS
  Serial.begin(38400);
  
  // prevent controller pins 5 and 6 from interfering with the comms from GPS
  pinMode(GPS_TX_DIGITAL_OUT_PIN, INPUT);
  pinMode(GPS_RX_DIGITAL_OUT_PIN, INPUT);
  
  startMillis = millis();
  Serial.println("Starting");
  
  SIM900.println("AT+CSQ"); // Signal quality check
  delay(100);
 
  // this code is to show the data from gprs shield, in order to easily see the process of how the gprs shield submit a http request, and the following is for this purpose too.
  //ShowSerialData();
  
  SIM900.println("AT+CGATT?"); //Attach or Detach from GPRS Support
  delay(100);
  //ShowSerialData();
  
  SIM900.println("AT+SAPBR=3,1,\"CONTYPE\",\"GPRS\"");//setting the SAPBR, the connection type is using gprs
  delay(1000);
  //ShowSerialData();
 
  SIM900.println("AT+SAPBR=3,1,\"APN\",\"CMNET\"");//setting the APN, Access point name string
  delay(4000);
  //ShowSerialData();
 
  SIM900.println("AT+SAPBR=1,1");//setting the SAPBR
  delay(2000);
  
  attachInterrupt(0, panicButton, CHANGE);
  attachInterrupt(1, checkMoneyWithdraw, CHANGE);

}


void loop() {
  
  Serial.println("Read Location");
  readLocation();
  delay(100);
  
  Serial.println("SubmitHttpRequest started");
  
  char lon[12];
  char lat[12];
  
  dtostrf(longitude, 12, 6, lon);
  dtostrf(latitude, 12, 6, lat);
  
  String sURL;
  
  sURL+="http://www.titansmora.org/logs/lan/";
  for(int x=0; x<12; x++){
    if(lon[x]!=' '){
      sURL+=lon[x];
    }
  }
  sURL+="/lat/";
  for(int x=0; x<12; x++){
    if(lat[x]!=' '){
      sURL+=lat[x];
    }
  }
  
  sURL+="/event/0/purse_id/" + purseID;
   
  SubmitHttpRequest(sURL);
  Serial.println("SubmitHttpRequest finished");
  
  isCheckPhone = digitalRead(cPhone);
  if(isCheckPhone){
    findPhone();
    isCheckPhone = 0;
  }
  
  delay(2000);
  
}


void SubmitHttpRequest(String url) {
  
  SIM900.println("AT+HTTPINIT"); //init the HTTP request
 
  delay(2000); 
  //ShowSerialData();
 
  SIM900.println("AT+HTTPPARA=\"URL\",\""+url+"\"");// setting the httppara, the second parameter is the website you want to access
  delay(1000);
  Serial.println(url);
  //ShowSerialData();
 
  SIM900.println("AT+HTTPACTION=0");//submit the request 
  delay(3000);//the delay is very important, the delay time is base on the return from the website, if the return datas are very large, the time required longer.
  //while(!SIM900.available());
  //ShowSerialData();
 
  SIM900.println("AT+HTTPREAD");// read the data from the website you access
  delay(300);
  //ShowSerialData();
 
  SIM900.println("");
  delay(100);
}


void panicButton() {
  
  Serial.println("Read Location");
  readLocation();
  delay(100);
  
  String sURL;
  char lon[12];
  char lat[12];
  
  sURL+="http://www.titansmora.org/logs/lan/";
  for(int x=0; x<12; x++){
    if(lon[x]!=' '){
      sURL+=lon[x];
    }
  }
  sURL+="/lat/";
  for(int x=0; x<12; x++){
    if(lat[x]!=' '){
      sURL+=lat[x];
    }
  }
  
  sURL+="/event/1/purse_id/" + purseID;
  SubmitHttpRequest(sURL);
  Serial.println("SubmitHttpRequest Panic - finished");
  delay(100);
  
}


void checkMoneyWithdraw() {
  
  int s1 = digitalRead(11);
  int s2 = digitalRead(12);
  if(!s1 && s2){
    isMoneyWithdraw = true;
  }
  if(s1 && !s2){
    isMoneyWithdraw = false;
  }

}


void moneyChange() {
  
  Serial.println("Read Location");
  readLocation();
  delay(100);
  
  String sURL;
  char lon[12];
  char lat[12];
  
  sURL+="http://www.titansmora.org/moneylogs/lan/";
  for(int x=0; x<12; x++){
    if(lon[x]!=' '){
      sURL+=lon[x];
    }
  }
  sURL+="/lat/";
  for(int x=0; x<12; x++){
    if(lat[x]!=' '){
      sURL+=lat[x];
    }
  }
  
  if(isMoneyWithdraw){
    sURL+="/money/1/purse_id/" + purseID;
  }
  else{
    sURL+="/money/0/purse_id/" + purseID;
  }
  
  SubmitHttpRequest(sURL);
  Serial.println("SubmitHttpRequest Panic - finished");
  delay(100);
  
}

void findPhone(){
  
  Serial.println("Read Location");
  readLocation();
  delay(100);
  
  String sURL;
  char lon[12];
  char lat[12];
  
  sURL+="http://www.titansmora.org/logs/lan/";
  for(int x=0; x<12; x++){
    if(lon[x]!=' '){
      sURL+=lon[x];
    }
  }
  sURL+="/lat/";
  for(int x=0; x<12; x++){
    if(lat[x]!=' '){
      sURL+=lat[x];
    }
  }
  
  sURL+="/event/3/purse_id/" + purseID;
  SubmitHttpRequest(sURL);
  Serial.println("SubmitHttpRequest Panic - finished");
  delay(100);

}


//--------------------------------------------------------------------------------------------
void readLocation() {
  bool newData = false;
  unsigned long chars = 0;
  unsigned short sentences, failed;

  // For one second we parse GPS data and report some key values
  for (unsigned long start = millis(); millis() - start < 1000;)
  {
    while (Serial.available())
    {
      int c = Serial.read();
      //Serial.print((char)c); // if you uncomment this you will see the raw data from the GPS
      ++chars;
      if (gps.encode(c)) // Did a new valid sentence come in?
        newData = true;
    }
  }
  
  if (newData)
  {
    // we have a location fix so output the lat / long and time to acquire
    if(secondsToFirstLocation == 0){
      secondsToFirstLocation = (millis() - startMillis) / 1000;
      Serial.print("Acquired in:");
      Serial.print(secondsToFirstLocation);
      Serial.println("s");
    }
    
    unsigned long age;
    gps.f_get_position(&latitude, &longitude, &age);
    
    latitude == TinyGPS::GPS_INVALID_F_ANGLE ? 0.0 : latitude;
    longitude == TinyGPS::GPS_INVALID_F_ANGLE ? 0.0 : longitude;
    
    Serial.print("Location: ");
    Serial.print(latitude, 6);
    Serial.print(" , ");
    Serial.print(longitude, 6);
    Serial.println("");
  }
  
  if (chars == 0){
    // if you haven't got any chars then likely a wiring issue
    Serial.println("Check wiring");
  }
  else if(secondsToFirstLocation == 0){
    Serial.println("Still working");
  }
}



