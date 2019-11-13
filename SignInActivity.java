package com.xposedstore;

import android.annotation.SuppressLint;
import android.app.ProgressDialog;
import android.content.Intent;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.database.Cursor;
import android.os.Build;
import android.os.Handler;
import android.os.Message;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.xposedstore.db.DatabaseHandler;
import com.xposedstore.services.HttpRequestsService;
import com.xposedstore.utils.PreferencesUtil;
import com.xposedstore.utils.XposedUtils;

import java.security.KeyStore;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Set;

public class SignInActivity extends AppCompatActivity {

    private XposedUtils xposedUtils = new XposedUtils();
    private TextView txtTopInstalls, txt1, txt2, txt3, txt1_downloads, txt2_downloads, txt3_downloads;
    private TextView txtlike_downloads1,txtlike_downloads2,txtlike_downloads3,txtlike_downloads4,txtlike_downloads5,txtlike_downloads6,txtlike_downloads7,txtlike_downloads8,txtlike_downloads9;
    private TextView txt1_new, txt2_new, txt3_new, txt4_new, txt_like1, txt_like2, txt_like3, txt_like4, txt_like5,txt_like6,txt_like7,txt_like8,txt_like9;
    private LinearLayout card1, card2, card3, card_new, card2_new, card3_new, card4_new;
    private LinearLayout card_like1, card_like2, card_like3, card_like4, card_like5, card_like6,card_like7,card_like8,card_like9;
    private ImageView imgUser, img_full1, img_full2, img_full3, img_full4;
    private DatabaseHandler databaseHandler;

    ProgressDialog myProgressDialog;
    private Handler updateBarHandler;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setTheme(R.style.AppTheme);

        int randomColor = xposedUtils.randomColor();

        if (Build.VERSION.SDK_INT >= 21){
            getWindow().setNavigationBarColor(randomColor);
            getWindow().setStatusBarColor(randomColor);
        }
        setContentView(R.layout.activity_sign_in);

        Toolbar toolbar = findViewById(R.id.my_toolbar);
        toolbar.setBackgroundColor(randomColor);
        toolbar.setTitle("");
        setSupportActionBar(toolbar);

        txtTopInstalls = findViewById(R.id.txtTopInstalls);
        card1 = findViewById(R.id.card1);
        card2 = findViewById(R.id.card2);
        card3 = findViewById(R.id.card3);
        imgUser = findViewById(R.id.imguser);
        txt1 = findViewById(R.id.txt1);
        txt2 = findViewById(R.id.txt2);
        txt3 = findViewById(R.id.txt3);
        img_full1 = findViewById(R.id.img_full1);
        img_full2 = findViewById(R.id.img_full2);
        img_full3 = findViewById(R.id.img_full3);
        img_full4 = findViewById(R.id.img_full4);
        txt1_downloads = findViewById(R.id.txt1_downloads);
        txt2_downloads = findViewById(R.id.txt2_downloads);
        txt3_downloads = findViewById(R.id.txt3_downloads);
        txt1_new = findViewById(R.id.txt1_new);
        txt2_new = findViewById(R.id.txt2_new);
        txt3_new = findViewById(R.id.txt3_new);
        txt4_new = findViewById(R.id.txt4_new);
        card_new = findViewById(R.id.card_new);
        card2_new = findViewById(R.id.card2_new);
        card3_new = findViewById(R.id.card3_new);
        card4_new = findViewById(R.id.card4_new);
        card_like1 = findViewById(R.id.card_like1);
        card_like2 = findViewById(R.id.card_like2);
        card_like3 = findViewById(R.id.card_like3);
        card_like4 = findViewById(R.id.card_like4);
        card_like5 = findViewById(R.id.card_like5);
        card_like6 = findViewById(R.id.card_like6);
        card_like7 = findViewById(R.id.card_like7);
        card_like8 = findViewById(R.id.card_like8);
        card_like9 = findViewById(R.id.card_like9);
        txt_like1 = findViewById(R.id.txt_like1);
        txt_like2 = findViewById(R.id.txt_like2);
        txt_like3 = findViewById(R.id.txt_like3);
        txt_like4 = findViewById(R.id.txt_like4);
        txt_like5 = findViewById(R.id.txt_like5);
        txt_like6 = findViewById(R.id.txt_like6);
        txt_like7 = findViewById(R.id.txt_like7);
        txt_like8 = findViewById(R.id.txt_like8);
        txt_like9 = findViewById(R.id.txt_like9);
        txtlike_downloads1 = findViewById(R.id.txtlike_downloads1);
        txtlike_downloads2 = findViewById(R.id.txtlike_downloads2);
        txtlike_downloads3 = findViewById(R.id.txtlike_downloads3);
        txtlike_downloads4 = findViewById(R.id.txtlike_downloads4);
        txtlike_downloads5 = findViewById(R.id.txtlike_downloads5);
        txtlike_downloads6 = findViewById(R.id.txtlike_downloads6);
        txtlike_downloads7 = findViewById(R.id.txtlike_downloads7);
        txtlike_downloads8 = findViewById(R.id.txtlike_downloads8);
        txtlike_downloads9 = findViewById(R.id.txtlike_downloads9);

        // set the random color
        txtTopInstalls.setTextColor(randomColor);
        card1.setBackgroundColor(xposedUtils.randomColor());
        card2.setBackgroundColor(xposedUtils.randomColor());
        card3.setBackgroundColor(xposedUtils.randomColor());
        card_new.setBackgroundColor(xposedUtils.randomColor());
        card2_new.setBackgroundColor(xposedUtils.randomColor());
        card3_new.setBackgroundColor(xposedUtils.randomColor());
        card4_new.setBackgroundColor(xposedUtils.randomColor());
        card_like1.setBackgroundColor(xposedUtils.randomColor());
        card_like2.setBackgroundColor(xposedUtils.randomColor());
        card_like3.setBackgroundColor(xposedUtils.randomColor());
        card_like4.setBackgroundColor(xposedUtils.randomColor());
        card_like5.setBackgroundColor(xposedUtils.randomColor());
        card_like6.setBackgroundColor(xposedUtils.randomColor());
        card_like7.setBackgroundColor(xposedUtils.randomColor());
        card_like8.setBackgroundColor(xposedUtils.randomColor());
        card_like9.setBackgroundColor(xposedUtils.randomColor());
        imgUser.setImageDrawable(xposedUtils.getUserTextDrawable(PreferencesUtil.username(SignInActivity.this)));

        selectTop3Modules();
        selectFullApps();
        selectRandomModules();

        if (!PreferencesUtil.firstRun(this)) {
            PreferencesUtil.isFirstRun(this, true);
        }

    }

    public void seeMore(View view){
        startActivity(new Intent(this, ModulesActivity.class));
    }
    public void card1FullApp(View view){
        openModule(txt1_new);
    }

    public void card2FullApp(View view){
        openModule(txt2_new);
    }

    public void card3FullApp(View view){
        openModule(txt3_new);
    }

    public void card4FullApp(View view){
        openModule(txt4_new);
    }

    public void cardLike1(View view){
        openModule(txt_like1);
    }

    public void cardLike2(View view){
        openModule(txt_like2);
    }

    public void cardLike3(View view){
        openModule(txt_like3);
    }

    public void cardLike4(View view){
        openModule(txt_like4);
    }

    public void cardLike5(View view){
        openModule(txt_like5);
    }

    public void cardLike6(View view){
        openModule(txt_like6);
    }

    public void cardLike7(View view){
        openModule(txt_like7);
    }

    public void cardLike8(View view){
        openModule(txt_like8);
    }

    public void cardLike9(View view){
        openModule(txt_like9);
    }

    public void openModule(TextView textView){
        String name = textView.getText().toString();
        Intent intent = new Intent(this, ModuleDetailsActivity.class);
        intent.putExtra("name", name);
        startActivity(intent);
    }

    public void selectFullApps(){
        databaseHandler = new DatabaseHandler(this);
        Cursor cursor = databaseHandler.fetchModules("full");
        ArrayList<String> list = new ArrayList<>();

        if (cursor != null) {
            while (cursor.moveToNext()) {
                String name = cursor.getString(1);
                // add to list
                list.add(name);
            }
        }

        String third = list.get(3);
        String second = list.get(2);
        String first = list.get(1);
        String zero_place = list.get(0);

        txt1_new.setText(third);
        txt2_new.setText(second);
        txt3_new.setText(zero_place);
        txt4_new.setText(first);

        // check if package already exists
        Cursor zero_cursor = databaseHandler.getPackage(zero_place);
        Cursor first_cursor = databaseHandler.getPackage(first);
        Cursor second_cursor = databaseHandler.getPackage(second);
        Cursor third_cursor = databaseHandler.getPackage(third);

        if (zero_cursor != null && zero_cursor.moveToFirst()){

            if (isPackageExist(zero_cursor.getString(0))) img_full3.setVisibility(View.VISIBLE);

        }

        if (first_cursor != null && first_cursor.moveToFirst()){
            if (isPackageExist(first_cursor.getString(0))) img_full4.setVisibility(View.VISIBLE);
        }

        if (second_cursor != null && second_cursor.moveToFirst()){
            if (isPackageExist(second_cursor.getString(0))) img_full2.setVisibility(View.VISIBLE);
        }

        if (third_cursor != null && third_cursor.moveToFirst()){
            if (isPackageExist(third_cursor.getString(0))) img_full1.setVisibility(View.VISIBLE);
        }

        // set to textviews
        cursor.close();
        zero_cursor.close();
        first_cursor.close();
        second_cursor.close();
        third_cursor.close();
        databaseHandler.close();
    }

    public void selectRandomModules(){
        databaseHandler = new DatabaseHandler(this);
        Cursor cursor = databaseHandler.getAllModules();
        ArrayList<String> list = new ArrayList<>();

        if (cursor != null) {
            while (cursor.moveToNext()) {
                String name = cursor.getString(1);
                // add to list
                list.add(name);
            }
        }

        String name1 = list.get(xposedUtils.randomNumber(list.size()));
        String name2 = list.get(xposedUtils.randomNumber(list.size()));
        String name3 = list.get(xposedUtils.randomNumber(list.size()));
        String name4 = list.get(xposedUtils.randomNumber(list.size()));
        String name5 = list.get(xposedUtils.randomNumber(list.size()));
        String name6 = list.get(xposedUtils.randomNumber(list.size()));
        String name7 = list.get(xposedUtils.randomNumber(list.size()));
        String name8 = list.get(xposedUtils.randomNumber(list.size()));
        String name9 = list.get(xposedUtils.randomNumber(list.size()));

        Cursor cursor1 = databaseHandler.getPackage(name1);
        if (cursor1 != null && cursor1.moveToFirst()) {
            Boolean exists = isPackageExist(cursor1.getString(0));
            // loop until the package doesn't exists
            while (exists) {
                name1 = list.get(xposedUtils.randomNumber(list.size()));
                cursor1 = databaseHandler.getPackage(name1);

                if (cursor1 != null && cursor1.moveToFirst()){
                    exists = isPackageExist(cursor1.getString(0));
                }
            }
        }

        Cursor cursor2 = databaseHandler.getPackage(name2);
        if (cursor2 != null && cursor2.moveToFirst()) {
            Boolean exists = isPackageExist(cursor2.getString(0));
            // loop until the package doesn't exists
            while (exists) {
                name2 = list.get(xposedUtils.randomNumber(list.size()));
                cursor2 = databaseHandler.getPackage(name2);

                if (cursor2 != null && cursor2.moveToFirst()){
                    exists = isPackageExist(cursor2.getString(0));
                }
            }
        }

        Cursor cursor3 = databaseHandler.getPackage(name3);
        if (cursor3 != null && cursor3.moveToFirst()) {
            Boolean exists = isPackageExist(cursor3.getString(0));
            // loop until the package doesn't exists
            while (exists) {
                name3 = list.get(xposedUtils.randomNumber(list.size()));
                cursor3 = databaseHandler.getPackage(name3);

                if (cursor3 != null && cursor3.moveToFirst()){
                    exists = isPackageExist(cursor3.getString(0));
                }
            }
        }

        Cursor cursor4 = databaseHandler.getPackage(name4);
        if (cursor4 != null && cursor4.moveToFirst()) {
            Boolean exists = isPackageExist(cursor4.getString(0));
            // loop until the package doesn't exists
            while (exists) {
                name4 = list.get(xposedUtils.randomNumber(list.size()));
                cursor4 = databaseHandler.getPackage(name4);

                if (cursor4 != null && cursor4.moveToFirst()){
                    exists = isPackageExist(cursor4.getString(0));
                }
            }
        }

        Cursor cursor5 = databaseHandler.getPackage(name5);
        if (cursor5 != null && cursor5.moveToFirst()) {
            Boolean exists = isPackageExist(cursor5.getString(0));
            // loop until the package doesn't exists
            while (exists) {
                name5 = list.get(xposedUtils.randomNumber(list.size()));
                cursor5 = databaseHandler.getPackage(name5);

                if (cursor5 != null && cursor5.moveToFirst()){
                    exists = isPackageExist(cursor5.getString(0));
                }
            }
        }

        Cursor cursor6 = databaseHandler.getPackage(name6);
        if (cursor6 != null && cursor6.moveToFirst()) {
            Boolean exists = isPackageExist(cursor6.getString(0));
            // loop until the package doesn't exists
            while (exists) {
                name6 = list.get(xposedUtils.randomNumber(list.size()));
                cursor6 = databaseHandler.getPackage(name6);

                if (cursor6 != null && cursor6.moveToFirst()){
                    exists = isPackageExist(cursor6.getString(0));
                }
            }
        }

        Cursor cursor7 = databaseHandler.getPackage(name7);
        if (cursor7 != null && cursor7.moveToFirst()) {
            Boolean exists = isPackageExist(cursor7.getString(0));
            // loop until the package doesn't exists
            while (exists) {
                name7 = list.get(xposedUtils.randomNumber(list.size()));
                cursor7 = databaseHandler.getPackage(name7);

                if (cursor7 != null && cursor7.moveToFirst()){
                    exists = isPackageExist(cursor7.getString(0));
                }
            }
        }

        Cursor cursor8 = databaseHandler.getPackage(name8);
        if (cursor8 != null && cursor8.moveToFirst()) {
            Boolean exists = isPackageExist(cursor8.getString(0));
            // loop until the package doesn't exists
            while (exists) {
                name8 = list.get(xposedUtils.randomNumber(list.size()));
                cursor8 = databaseHandler.getPackage(name8);

                if (cursor8 != null && cursor8.moveToFirst()){
                    exists = isPackageExist(cursor8.getString(0));
                }
            }
        }

        Cursor cursor9 = databaseHandler.getPackage(name9);
        if (cursor9 != null && cursor9.moveToFirst()) {
            Boolean exists = isPackageExist(cursor9.getString(0));
            // loop until the package doesn't exists
            while (exists) {
                name9 = list.get(xposedUtils.randomNumber(list.size()));
                cursor9 = databaseHandler.getPackage(name9);

                if (cursor9 != null && cursor9.moveToFirst()){
                    exists = isPackageExist(cursor9.getString(0));
                }
            }
        }

        txt_like1.setText(name1);
        txt_like2.setText(name2);
        txt_like3.setText(name3);
        txt_like4.setText(name4);
        txt_like5.setText(name5);
        txt_like6.setText(name6);
        txt_like7.setText(name7);
        txt_like8.setText(name8);
        txt_like9.setText(name9);

        // get downloads
        Cursor downloads1 = databaseHandler.returnDownloads(name1);
        Cursor downloads2 = databaseHandler.returnDownloads(name2);
        Cursor downloads3 = databaseHandler.returnDownloads(name3);
        Cursor downloads4 = databaseHandler.returnDownloads(name4);
        Cursor downloads5 = databaseHandler.returnDownloads(name5);
        Cursor downloads6 = databaseHandler.returnDownloads(name6);
        Cursor downloads7 = databaseHandler.returnDownloads(name7);
        Cursor downloads8 = databaseHandler.returnDownloads(name8);
        Cursor downloads9 = databaseHandler.returnDownloads(name9);

        if (downloads1 != null && downloads1.moveToFirst()) txtlike_downloads1.setText(downloads1.getString(0));
        if (downloads2 != null && downloads2.moveToFirst()) txtlike_downloads2.setText(downloads2.getString(0));
        if (downloads3 != null && downloads3.moveToFirst()) txtlike_downloads3.setText(downloads3.getString(0));
        if (downloads4 != null && downloads4.moveToFirst()) txtlike_downloads4.setText(downloads4.getString(0));
        if (downloads5 != null && downloads5.moveToFirst()) txtlike_downloads5.setText(downloads5.getString(0));
        if (downloads6 != null && downloads6.moveToFirst()) txtlike_downloads6.setText(downloads6.getString(0));
        if (downloads7 != null && downloads7.moveToFirst()) txtlike_downloads7.setText(downloads7.getString(0));
        if (downloads8 != null && downloads8.moveToFirst()) txtlike_downloads8.setText(downloads8.getString(0));
        if (downloads9 != null && downloads9.moveToFirst()) txtlike_downloads9.setText(downloads9.getString(0));

        cursor.close();
        cursor1.close();
        cursor2.close();
        cursor3.close();
        cursor4.close();
        cursor5.close();
        cursor6.close();
        cursor7.close();
        cursor8.close();
        cursor9.close();
        downloads1.close();
        downloads2.close();
        downloads3.close();
        downloads4.close();
        downloads5.close();
        downloads6.close();
        downloads7.close();
        downloads8.close();
        downloads9.close();
        databaseHandler.close();
    }

    public void selectTop3Modules(){
        databaseHandler = new DatabaseHandler(this);
        Cursor cursor = databaseHandler.getAllModules();
        HashMap<String, Integer> map = new HashMap<>();

        int startIndex = 0;
        int endIndex = 1;
        int newStartIndex = 0;
        int newEndIndex = 0;
        int secondStartIndex = 0;
        int secondEndIndex = 0;

        /**
         * Fetch names and download numbers
         * put them in a hashmap then sort
         * get the top 3
         */

        while (cursor.moveToNext()){
            String name = cursor.getString(1);
            int downloads = Integer.parseInt(cursor.getString(7));

            map.put(name, downloads);

        }

        Set<Map.Entry<String, Integer>> set = map.entrySet();
        List<Map.Entry<String, Integer>> list = new ArrayList<>(set);

        Collections.sort(list, new Comparator<Map.Entry<String, Integer>>() {
            @Override
            public int compare(Map.Entry<String, Integer> stringIntegerEntry, Map.Entry<String, Integer> t1) {
                return t1.getValue().compareTo(stringIntegerEntry.getValue());
            }
        });

        String first = list.subList(startIndex, endIndex).toString().replaceAll("\\d","");
        first = first.replaceAll("[^a-zA-Z0-9\\s+]", "");

        String second = list.subList(startIndex, endIndex).toString().replaceAll("\\d","");
        second = second.replaceAll("[^a-zA-Z0-9\\s+]", "");

        String third = list.subList(startIndex, endIndex).toString().replaceAll("\\d","");
        third = third.replaceAll("[^a-zA-Z0-9\\s+]", "");


        /***
         * we are going to get the package name and check if it exists (installed)
         * if true, increment the start and end indexes
         * check the package for the next item
         * and repeat until false is returned
         */


        Cursor cursor1 = databaseHandler.getPackage(first);
        if (cursor1 != null && cursor1.moveToFirst()){
            Boolean exists = isPackageExist(cursor1.getString(0));
            // loop until the package doesn't exists
            while (exists){

                first = list.subList(startIndex++, endIndex++).toString().replaceAll("\\d","");
                first = first.replaceAll("[^a-zA-Z0-9\\s+]", "");

                // search for the next package
                cursor1 = databaseHandler.getPackage(first);
                if (cursor1 != null && cursor1.moveToFirst()){
                    exists = isPackageExist(cursor1.getString(0));
                }

            }

            newStartIndex = startIndex;
            newEndIndex = endIndex;

            //System.out.println("start and end Indexes for "+ first +" " + String.valueOf(startIndex) + " - " + String.valueOf(endIndex));

        }

        Cursor cursor2 = databaseHandler.getPackage(second);
        if (cursor2 != null && cursor2.moveToFirst()){
            Boolean exists = isPackageExist(cursor2.getString(0));
            // loop until the package doesn't exists
            while (exists){
                second = list.subList(newStartIndex++, newEndIndex++).toString().replaceAll("\\d","");
                second = second.replaceAll("[^a-zA-Z0-9\\s+]", "");

                // search for the next package
                cursor2 = databaseHandler.getPackage(second);
                if (cursor2 != null && cursor2.moveToFirst()){
                    exists = isPackageExist(cursor2.getString(0));
                }

            }

            secondStartIndex = newStartIndex;
            secondEndIndex = newEndIndex;

            //System.out.println("start and end Indexes for "+ second +" " + String.valueOf(newStartIndex) + " - " + String.valueOf(newEndIndex));

        }

        Cursor cursor3 = databaseHandler.getPackage(third);
        if (cursor3 != null && cursor3.moveToFirst()){
            Boolean exists = isPackageExist(cursor3.getString(0));
            // loop until the package doesn't exists
            while (exists){
                third = list.subList(secondStartIndex++, secondEndIndex++).toString().replaceAll("\\d","");
                third = third.replaceAll("[^a-zA-Z0-9\\s+]", "");

                // search for the next package
                cursor3 = databaseHandler.getPackage(third);
                if (cursor3 != null && cursor3.moveToFirst()){
                    exists = isPackageExist(cursor3.getString(0));
                }

            }

            //System.out.println("start and end Indexes for "+ third +" " + String.valueOf(secondStartIndex) + " - " + String.valueOf(secondEndIndex));

        }

        /**
         * fetch downloads
         */

        txt1.setText(first);
        txt2.setText(second);
        txt3.setText(third);

        Cursor first_downloads = databaseHandler.returnDownloads(first);
        Cursor second_downloads = databaseHandler.returnDownloads(second);
        Cursor third_downloads = databaseHandler.returnDownloads(third);

        if (first_downloads != null && first_downloads.moveToFirst()) txt1_downloads.setText(first_downloads.getString(0));
        if (second_downloads != null && second_downloads.moveToFirst()) txt2_downloads.setText(second_downloads.getString(0));
        if (third_downloads != null && third_downloads.moveToFirst()) txt3_downloads.setText(third_downloads.getString(0));

        cursor.close();
        cursor1.close();
        cursor2.close();
        cursor3.close();
        first_downloads.close();
        second_downloads.close();
        third_downloads.close();
        databaseHandler.close();
    }

    public boolean isPackageExist(String targetPackage){
        PackageManager pm=getPackageManager();
        try {
            PackageInfo info=pm.getPackageInfo(targetPackage,PackageManager.GET_META_DATA);
        } catch (PackageManager.NameNotFoundException e) {
            return false;
        }
        return true;
    }

    public void seeMoreFullApps(View view){
        startActivity(new Intent(this, FullFeaturedAppsActivity.class));
    }

    public void card1Clicked(View view)
    {
        openModule(txt1);
    }

    public void card2Clicked(View view){
        openModule(txt2);
    }

    public void card3Clicked(View view){
        openModule(txt3);
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        MenuInflater inflater = getMenuInflater();
        inflater.inflate(R.menu.signed_in, menu);

        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle item selection
        switch (item.getItemId()) {
            case R.id.settings:
                // show dialog

                return true;
            default:
                return super.onOptionsItemSelected(item);
        }
    }
}
