%% MICS - Alquimics - neural-network learning and prediction

%% To execute the program run "mics" in a command window.

%% Initialisation
clear ; close all; clc

%% =========== Part 1: Loading and visualising data =============
% We start by first loading and visualising the dataset.
% We will be working with a dataset that contains
% citizen-science project characteristics.

% Load training data (3 training datasets) (needs to be changed if the number
% of datasets is different)
fprintf('Loading data...\n')
pause (1);
X1=csvread('X01.csv');
X2=csvread('X02.csv');
X3=csvread('X03.csv');
X4=csvread('X04.csv');
X5=csvread('X05.csv');
X6=csvread('X06.csv');
X7=csvread('X07.csv');
X8=csvread('X08.csv');
X9=csvread('X09.csv');

% Unroll and merge input matrices
X = [X1'(:)' ; X2'(:)' ; X3'(:)' ; X4'(:)' ; X5'(:)' ; X6'(:)' ; X7'(:)' ; X8'(:)' ; X9'(:)'];

y=csvread('Y.csv')./42;
m = size(X, 1);

%% Setup of the parameters
input_layer_size  = size(X, 2);   % number of questions x number of options (20)
hidden_layer_size = 42;           % 42 hidden units; this can be up to 4000
num_labels = 5;                   % 5 labels for the domains, from 1 to 5
% If the number of domains changes, line 66 of file "nnCostFunction" needs to
% be changed accordingly

% Randomly select 2 data points to display
% sel = randperm(size(X, 1));
% sel = sel(1:3);
% disp(X(sel, :));

% fprintf('Program paused. Press enter to continue.\n');
% pause;

%% ================ Part 2: Loading parameters ================
% In this part, we randomly initialise the neural network parameters.

fprintf('\nInitialising neural-network parameters...\n')
pause (1);
% Initialise the weights into variables Theta1 and Theta2
epsilon_init1 = 0.029;
Theta1 = (2*epsilon_init1).*rand(hidden_layer_size,input_layer_size+1)-epsilon_init1;
epsilon_init2 = 0.039;
Theta2 = (2*epsilon_init2).*rand(5,hidden_layer_size+1)-epsilon_init2;

% Unroll parameters
initial_nn_params = [Theta1(:) ; Theta2(:)];

%% ================ Part 3: Compute cost (Feedforward) ================
%  We first start by implementing the
%  feedforward part of the neural network that returns the cost only.
%  nnCostFunction.m returns the cost.

fprintf('\nStarting neural network...\n')
pause (1);
% Weight regularization parameter (we set this to 0 here).
% lambda = 0;

% J = nnCostFunction(initial_nn_params, input_layer_size, hidden_layer_size, ...
%                   num_labels, X, y, lambda);

%fprintf(['Cost at initialisation parameters without regularisation: %f '...
%         '\n\n'], J);

% fprintf('\nProgram paused. Press enter to continue.\n');
% pause;

%% =============== Part 4: Implement regularisation ===============
%  We now check the regularisation with the cost.

fprintf('\nCalculating cost function with regularisation (to avoid overfitting)... \n\n')
pause (1);
% Weight regularisation parameter (we set this to 1 here).
lambda = 1;

J = nnCostFunction(initial_nn_params, input_layer_size, hidden_layer_size, ...
                   num_labels, X, y, lambda);

fprintf(['Cost function corresponding to initialisation parameters: %f '...
         '\n'], J);
pause (1);
% fprintf('Program paused. Press enter to continue.\n');
% pause;


%% ================ Part 5: Sigmoid gradient  ================
%  We check the gradient for the sigmoid function.

fprintf('\nEvaluating sigmoid gradient... \n')
pause (1);
fprintf('\nSigmoid gradient at [-1 -0.5 0 0.5 1]: ')
pause (1);
g = sigmoidGradient([-1 -0.5 0 0.5 1]);
fprintf('%f ', g);

% fprintf('Program paused. Press enter to continue.\n');
pause(1);

%% =================== Part 6: Training NN ===================
% We have now all the code necessary to train a neural
% network. To train the neural network, we will now use "fmincg".
% This advanced optimizer is able to train our cost functions efficiently as
% long as we provide it with the gradient computations.

fprintf('\n\nTraining neural network... \n\n')

%  The number of iterations can be changed to a larger
%  value to see how more training helps.
options = optimset('MaxIter', 10);

%  Different values of lambda should be tried.
lambda = 0.07;

% Create "short hand" for the cost function to be minimized
costFunction = @(p) nnCostFunction(p, ...
                                   input_layer_size, ...
                                   hidden_layer_size, ...
                                   num_labels, X, y, lambda);

% Now, costFunction is a function that takes in only one argument (the
% neural network parameters)
[nn_params, cost] = fmincg(costFunction, initial_nn_params, options);

% Obtain Theta1 and Theta2 back from nn_params
Theta1 = reshape(nn_params(1:hidden_layer_size * (input_layer_size + 1)), ...
                 hidden_layer_size, (input_layer_size + 1));

Theta2 = reshape(nn_params((1 + (hidden_layer_size * (input_layer_size + 1))):end), ...
                 num_labels, (hidden_layer_size + 1));

% fprintf('Program paused. Press enter to continue.\n');
% pause;

%% ================= Part 7: Implement predict =================
%  After training the neural network, we use it to predict
%  the labels. The "predict" function uses the
%  neural network to predict the labels of the training set. This lets
%  us compute the training set accuracy.

pred = predict(Theta1, Theta2, X);
pred42 = pred .*42;

fprintf('Training-set accuracy per domain: %f\n', (1 - mean(sqrt(double(pred - y).^2))) * 100);

save Theta1.mat Theta1;
save Theta2.mat Theta2;
