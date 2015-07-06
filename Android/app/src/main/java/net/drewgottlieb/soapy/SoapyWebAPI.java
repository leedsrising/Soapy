package net.drewgottlieb.soapy;

import org.apache.http.client.HttpClient;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.params.BasicHttpParams;
import org.jdeferred.Deferred;
import org.jdeferred.Promise;
import org.jdeferred.impl.DeferredObject;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedInputStream;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.ArrayList;
import java.util.List;

/**
 * Created by drew on 7/5/15.
 */
public class SoapyWebAPI {
    public static class SoapyWebError extends Exception {
        public SoapyWebError(String message) {
            super(message);
        }
    }

    private String host;
    private int port;
    private boolean secure;

    private static SoapyWebAPI instance = null;

    public static SoapyWebAPI getInstance() {
        if (instance == null) {
            // TODO: Load from a config, don't hard code.
            instance = new SoapyWebAPI("soapy.csh.rit.edu", 80, false);
        }

        return instance;
    }

    protected SoapyWebAPI(String host, int port, boolean secure) {
        this.host = host;
        this.port = port;
        this.secure = secure;
    }

    public Promise<JSONObject, SoapyWebError, Void> get(String route) {
        final Deferred<JSONObject, SoapyWebError, Void> deferred = new DeferredObject<JSONObject, SoapyWebError, Void>();

        String protocol = secure ? "https://" : "http://";
        URL url = null;
        try {
            url = new URL(protocol + host + ":" + port + "/" + route);
        } catch (MalformedURLException e) {
            e.printStackTrace();
            deferred.reject(new SoapyWebError("Malformed URL: " + url));
            return deferred.promise();
        }
        final URL fURL = url;

        (new Thread() {
            public void run() {
                HttpURLConnection conn = null;
                String result = null;
                try {
                    conn = (HttpURLConnection) fURL.openConnection();
                    BufferedReader in = new BufferedReader(new InputStreamReader(conn.getInputStream(), "UTF-8"), 8);
                    StringBuilder sb = new StringBuilder();

                    String line = null;
                    while ((line = in.readLine()) != null) {
                        sb.append(line + "\n");
                    }

                    result = sb.toString();
                } catch (IOException e) {
                    e.printStackTrace();
                    deferred.reject(new SoapyWebError("IOException: " + e.getMessage()));
                    return;
                } finally {
                    if (conn != null) {
                        conn.disconnect();
                    }
                }

                JSONObject obj = null;
                try {
                    obj = new JSONObject(result);
                } catch (JSONException e) {
                    e.printStackTrace();
                    deferred.reject(new SoapyWebError("JSONException: " + e.getMessage()));
                    return;
                }

                if (obj.has("error")) {
                    try {
                        deferred.reject(new SoapyWebError(obj.getString("error")));
                    } catch (JSONException e) {
                        e.printStackTrace();
                        deferred.reject(new SoapyWebError("Failed to read response error."));
                    }
                    return;
                }

                deferred.resolve(obj);
            }
        }).start();

        return deferred.promise();
    }
}
