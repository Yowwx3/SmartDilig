
#define BLYNK_TEMPLATE_ID "TMPL6tvt9-Q82"
#define BLYNK_TEMPLATE_NAME "NodeMCU test"
#define BLYNK_AUTH_TOKEN "l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9"
#include <SoftwareSerial.h>

#define BLYNK_PRINT Serial

//for sql connection
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>

//for cloud connection
#include <ESP8266WiFi.h>
#include <BlynkSimpleEsp8266.h>


#define waterPump D0
#define msensor A0
#define RE D3
#define DE D2


int msvalue = 0;  // moisture sensor value
int mspercent;  // moisture value in percentage

bool Relay = 0;
const char* serverName = "http://smartdilig.passionatepanda.online/insert_data.php";


// Your WiFi credentials.
// Set password to "" for open networks.
char ssid[] = "Dap pretty girl";
char pass[] = "browny123";
BlynkTimer timer; 


const byte nitro[] = { 0x01, 0x03, 0x00, 0x1e, 0x00, 0x01, 0xe4, 0x0c };
const byte phos[] = { 0x01, 0x03, 0x00, 0x1f, 0x00, 0x01, 0xb5, 0xcc };
const byte pota[] = { 0x01, 0x03, 0x00, 0x20, 0x00, 0x01, 0x85, 0xc0 };
byte values[11];
SoftwareSerial mod(D5, D6);




void setup() {
  // Debug console
  Serial.begin(9600);
  mod.begin(9600);
  pinMode(RE, OUTPUT);
  pinMode(DE, OUTPUT);


  //Blynk.begin(BLYNK_AUTH_TOKEN, ssid, pass);
  Blynk.begin(BLYNK_AUTH_TOKEN, ssid, pass, "blynk.cloud", 80);
  pinMode(waterPump, OUTPUT);
  digitalWrite(waterPump, HIGH);

  pinMode(msensor, INPUT);

  WiFi.begin(ssid, pass);
  Serial.println("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
}
BLYNK_WRITE(V1) {
  Relay = param.asInt();

  if (Relay == 1) {
    digitalWrite(waterPump, LOW);
  } else {
    digitalWrite(waterPump, HIGH);
  }
  delay(100);
}


void loop() {
  
  byte val1, val2, val3;
  Blynk.run();
  val1 = nitrogen();
  delay(500);
  val2 = phosphorous();
  delay(500);
  val3 = potassium();
  delay(500);

  Serial.print("Nitrogen: ");
  Serial.print(val1);
  Serial.println(" mg/kg");
  Serial.print("Phosphorous: ");
  Serial.print(val2);
  Serial.println(" mg/kg");
  Serial.print("Potassium: ");
  Serial.print(val3);
  Serial.println(" mg/kg");
 

  msvalue = analogRead(msensor);
  mspercent = map(msvalue, 0, 1023, 0, 100);  // To display the soil moisture value in percentage
  mspercent = (mspercent - 100) * -1;

  Blynk.virtualWrite(V0, mspercent);
  Blynk.virtualWrite(V2, val1);
  Blynk.virtualWrite(V3, val2);
  Blynk.virtualWrite(V4, val3);

  if (WiFi.status() == WL_CONNECTED) {
    WiFiClient client;
    HTTPClient http;


    http.begin(client, serverName);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    // Prepare HTTP POST request data
    String httpRequestData = "value1=" + String(mspercent)+
                          "&value2=" + String(val1) +
                          "&value3=" + String(val2) +
                          "&value4=" + String(val3);
    Serial.print("httpRequestData: ");
    Serial.println(httpRequestData);

    // Send HTTP POST request
    int httpResponseCode = http.POST(httpRequestData);


    if (httpResponseCode > 0) {
      Serial.print("HTTP Response code: ");
      Serial.println(httpResponseCode);
    } else {
      Serial.print("Error code: ");
      Serial.println(httpResponseCode);
    }
    // Free resources
    http.end();
  } else {
    Serial.println("WiFi Disconnected");
  }

  delay(3000);


}




byte nitrogen() {
  digitalWrite(DE, HIGH);
  digitalWrite(RE, HIGH);
  delay(10);
  if (mod.write(nitro, sizeof(nitro)) == 8) {
    digitalWrite(DE, LOW);
    digitalWrite(RE, LOW);
    for (byte i = 0; i < 7; i++) {
      //Serial.print(mod.read(),HEX);
      values[i] = mod.read();
      Serial.print(values[i], HEX);
    }
    Serial.println();
  }
  return values[4];
}

byte phosphorous() {
  digitalWrite(DE, HIGH);
  digitalWrite(RE, HIGH);
  delay(10);
  if (mod.write(phos, sizeof(phos)) == 8) {
    digitalWrite(DE, LOW);
    digitalWrite(RE, LOW);
    for (byte i = 0; i < 7; i++) {
      //Serial.print(mod.read(),HEX);
      values[i] = mod.read();
      Serial.print(values[i], HEX);
    }
    Serial.println();
  }
  return values[4];
}

byte potassium() {
  digitalWrite(DE, HIGH);
  digitalWrite(RE, HIGH);
  delay(10);
  if (mod.write(pota, sizeof(pota)) == 8) {
    digitalWrite(DE, LOW);
    digitalWrite(RE, LOW);
    for (byte i = 0; i < 7; i++) {
      //Serial.print(mod.read(),HEX);a
      values[i] = mod.read();
      Serial.print(values[i], HEX);
    }
    Serial.println();
  }
  return values[4];
}
