%% MICS - Alquimics - neural-network learning and prediction

%% To execute the program run "mics" in a command window.

%% Initialisation
clear ; close all; clc

%% =========== Part 1: Loading and visualising data =============
% We start by first loading and visualising the dataset.
% We will be working with a dataset that contains
% citizen-science project characteristics.

% Load training data
fprintf('AI starting...\n')
pause (1);
X1=csvread('X01.csv');

% Unroll and merge input matrices
X = [X1'(:)'];
m = size(X, 1);

%% Setup of the parameters
input_layer_size  = size(X, 2);   % number of questions x number of options (20)
hidden_layer_size = 42;           % 42 hidden units
num_labels = 5;                   % 5 labels for the domains, from 1 to 5
% If the number of domains changes, line 66 of file "nnCostFunction" needs to
% be changed accordingly

% Randomly select 2 data points to display
% sel = randperm(size(X, 1));
% sel = sel(1:3);
% disp(X(sel, :));

% fprintf('Program paused. Press enter to continue.\n');
% pause;



% Obtain Theta1 and Theta2 back from nn_params
load Theta1.mat;
load Theta2.mat;


% fprintf('Program paused. Press enter to continue.\n');
% pause;

%% ================= Part 7: Implement predict =================
%  After training the neural network, we use it to predict
%  the labels. The "predict" function uses the
%  neural network to predict the labels of the training set. This lets
%  us compute the training set accuracy.

pred = predict(Theta1, Theta2, X);
pred42 = pred .*42;
csvwrite ('impact.csv', pred42);


