package com.xposedstore.utils;

import android.accounts.Account;
import android.accounts.AccountManager;
import android.content.Context;
import android.util.Patterns;

import com.amulyakhare.textdrawable.TextDrawable;
import com.amulyakhare.textdrawable.util.ColorGenerator;

import java.util.Calendar;
import java.util.Random;
import java.util.TimeZone;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class XposedUtils {
    Random random = new Random();
    public final String DATA = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz|!£$%&/=@#";

    public final static String tagStart=
            "\\<\\w+((\\s+\\w+(\\s*\\=\\s*(?:\".*?\"|'.*?'|[^'\"\\>\\s]+))?)+\\s*|\\s*)\\>";
    public final static String tagEnd=
            "\\</\\w+\\>";
    public final static String tagSelfClosing=
            "\\<\\w+((\\s+\\w+(\\s*\\=\\s*(?:\".*?\"|'.*?'|[^'\"\\>\\s]+))?)+\\s*|\\s*)/\\>";
    public final static String htmlEntity=
            "&[a-zA-Z][a-zA-Z0-9]+;";
    public final static Pattern htmlPattern=Pattern.compile(
            "("+tagStart+".*"+tagEnd+")|("+tagSelfClosing+")|("+htmlEntity+")",
            Pattern.DOTALL
    );

    public int randomColor(){
        ColorGenerator generator = ColorGenerator.MATERIAL;
        return generator.getColor(random.nextInt(100-10)+1)+10;
    }

    public TextDrawable getUserTextDrawable(String name){
        // make first letter cap
        name = name.substring(0, 1).toUpperCase() + name.substring(1);
        name = String.valueOf(name.charAt(0));
        ColorGenerator generator = ColorGenerator.MATERIAL;
        int color = generator.getColor(random.nextInt(100-10)+1)+10;

        TextDrawable textDrawable = TextDrawable.builder()
                .buildRound(name, color);

        return textDrawable;
    }

    public int randomNumber(int range){
        return random.nextInt(range);
    }

    public Integer getOffsetHours(){
        TimeZone timezone = TimeZone.getDefault();
        int seconds = timezone.getOffset(Calendar.ZONE_OFFSET)/1000;
        int minutes = seconds/60;
        int hours = minutes/60;

        return hours;
    }

    public String returnGoogleEmail(Context context){
        String email = null;

        Pattern gmailPattern = Patterns.EMAIL_ADDRESS;
        Account[] accounts = AccountManager.get(context).getAccounts();
        for (Account account : accounts) {
            if (gmailPattern.matcher(account.name).matches()) {
                email = account.name;
            }
        }
        return email;
    }

    public String randomString(int len) {
            StringBuilder sb = new StringBuilder(len);

            for (int i = 0; i < len; i++) {
                sb.append(DATA.charAt(random.nextInt(DATA.length())));
            }

            return sb.toString();
    }

    public boolean isHtml(String s) {
        boolean ret = false;
        if (s != null) {
            ret = htmlPattern.matcher(s).find();
        }
        return ret;
    }

    public String thousandSeparator(int number){
        return String.format("%,d\n", number);
    }

}
